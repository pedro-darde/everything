import './bootstrap';
import {duplicateInitialPageProps, initAuthInterceptor, initInertiaApp} from "./shared/setup";
initInertiaApp({
    pages: import.meta.glob('./pages/**/*.vue', { eager: true }),
    onSetup(appInstance, store, props) {
        initAuthInterceptor(store)
        duplicateInitialPageProps(store, props)
    },
    mixin: {
        methods: {
            devicePlatform() {
                const userAgent =
                    navigator.userAgent || navigator.vendor || window.opera

                if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
                    return 'iOS'
                }

                if (/android/i.test(userAgent)) {
                    return 'Android'
                }

                return 'Desktop'
            },
            isNativeMapAppAvailable() {
                const platform = this.devicePlatform()

                return platform === 'iOS' || platform === 'Android'
            },
        },
    },
}).then((r) => {
    console.log('Inertia app created')
})

