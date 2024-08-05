<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Responses\InvalidValidationResponse;
use App\Models\TemplateValidator;
use App\Models\TemplateValidatorFields;
use App\Services\CreateTemplateValidatorService;
use App\Services\EditTemplateValidatorService;
use App\Services\Validator\Extractors\CSVExtractor;
use App\Services\Validator\Extractors\JsonExtractor;
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
        dump(TemplateValidatorFields::with('template')->get()->groupBy('template.name'));
        return Inertia::render('Validator/Edit/Index',
            [
                'template' => TemplateValidator::with('fields')->find($id),
                'referencesFields' => TemplateValidatorFields::with('template')->get()->groupBy('template.name')
            ]
        );
    }

    public function create(): \Inertia\Response
    {

        return Inertia::render('Validator/CreateEdit/Index');
    }

    public function createByJson(): \Inertia\Response
    {
        return Inertia::render('Validator/Create/ByJson');
    }

    public function postCreateByJson(Request $request): JsonResponse {
        try {
            $this->createService->create($request->all());

            return response()->json([
                'message' => 'Template created successfully'
            ], 201);

        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return InvalidValidationResponse::send($e);
            }

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
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

    public function postEdit(Request $request): JsonResponse
    {
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
            $template = TemplateValidator::query()->findOrFail($id);
            $template->fields()->delete();
            $template->delete();
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

        $csvExtractor = new CSVExtractor($file, $request->get('templateOnly') === 'true');
        $templateExtractor = new TemplateExtractor($csvExtractor);
        $templates = $templateExtractor->getFileInformation();
        return response()->json([
            'templates' => $templates
        ]);
    }

    public function identifyJsonData(Request $request): JsonResponse
    {
        try {
            $file = $request->file('file');

            $fileInfo =
                (new TemplateExtractor(new JsonExtractor($file)))
                    ->getFileInformation();

            return response()->json([
                'data' => $fileInfo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

    }

    public function import(Request $request): \Inertia\Response
    {
        return Inertia::render("Validator/Import");
    }
}
