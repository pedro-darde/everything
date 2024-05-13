<?php

namespace App\Http\Controllers;

use App\Models\TemplateValidator;
use App\Services\CreateTemplateValidatorService;
use App\Services\EditTemplateValidatorService;
use App\Services\Validator\TemplateExtractor;
use App\Services\Validator\ValidatorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ValidatorController extends Controller
{

    public function __construct(
        private readonly CreateTemplateValidatorService $createService,
        private readonly EditTemplateValidatorService $editService
    ) {
    }
    public function index(): \Inertia\Response
    {
        return Inertia::render('Validator/Index', [
            'data' => TemplateValidator::with('fields')->paginate(10),
        ]);
    }

    public function edit(Request $request, $id): \Inertia\Response
    {
        return Inertia::render('Validator/Edit/Index',
            [
                'template' => TemplateValidator::with('fields')->find($id)
            ]
        );
    }

    public function create(): \Inertia\Response
    {
        return Inertia::render('Validator/CreateEdit/Index');
    }

    public function postCreate(Request $request): JsonResponse
    {
        try {
            $this->createService->create($request->all());
            return response()->json([
                'message' => 'Template created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function postEdit(Request $request) {
        try {
            $this->editService->edit($request->all());
            return response()->json([
                'message' => 'Template updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function loadMore(Request $request) {
        $searchString = $request->query('search');
        $perPage = $request->query('per_page') == -1 ? 0 : $request->query('per_page');
        $orderBy = $request->query("order_by") ?? "id";
        $direction = $request->query("order_direction") ?? "asc";

        $templates = TemplateValidator::query()
            ->with('fields')
            ->when(
                isset($perPage) && $perPage > 0,
                fn ($query) => $query
                    //                    ->whereRaw("(name like '%$searchString%' OR last_name LIKE '%$searchString%' or cpf LIKE '%$searchString%' OR ('$searchString' = ''))")
                    ->orderBy($orderBy, $direction)
                    ->paginate($perPage),
                fn ($query) => $query
                    //                    ->whereRaw("(name like '%$searchString%' OR last_name LIKE '%$searchString%' or cpf LIKE '%$searchString%' OR ($searchString = $searchString))")
                    ->orderBy($orderBy, $direction)
                    ->get()
            );

        return response()->json([
            'data' => $templates,
        ]);
    }

    public function delete(int $id): JsonResponse
    {
        try {
            TemplateValidator::query()->find($id)->delete();

            return response()->json([
                'message' => 'Template deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function identifyTemplates(Request $request): JsonResponse
    {
        $file = $request->file('file');
        $templateExtractor = new TemplateExtractor($file, $request->get('templateOnly') === 'true');
        $templates = $templateExtractor->getTemplates();
        return response()->json([
            'templates' => $templates
        ]);
    }

    public function import(Request $request)
    {
        return Inertia::render("Validator/Import");
    }
}
