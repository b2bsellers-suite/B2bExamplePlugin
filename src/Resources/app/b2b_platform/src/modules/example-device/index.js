import './pages/example-devices-index';
import './pages/example-devices-detail';
import './pages/example-devices-create';

const { Module } = B2bPlatform;


Module.register('example_devices', {
    name: 'example_devices',
    routes: {
        index: {
            component: 'example-devices-index',
            path: null
        },
        detail: {
            component: 'example-devices-detail',
            path: 'detail/:id'
        },
        create: {
            component: 'example-devices-create',
            path: 'create'
        }
    }
});