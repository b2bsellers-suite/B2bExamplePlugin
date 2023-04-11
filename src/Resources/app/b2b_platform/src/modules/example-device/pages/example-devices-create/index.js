import template from './template.html.twig';
const {Component} = B2bPlatform;
const cloneDeep = require('lodash.clonedeep');

Component.extend('example-devices-create', 'example-devices-detail', {
    template,

    data() {
        return {
            isLoading: true,
            _initialData: null,
            data: {
                name: null,
                description: null,
                serial_number: null,
                employeeId: null,
                start_at: null,
                end_at: null
            },
        }
    },

    methods: {
        createComponent() {
            this.isLoading = false;
            this.data.employeeId = this.employee.id;
        },

        save() {

            const data = cloneDeep(this.data);

            this.isLoading = true;

            this.apiService.post('example-devices', data).then(() => {
                this.isLoading = false;

                this.$notify({
                    type: 'success',
                    title: this.$trans('b2bPlatform.messages.successTitle'),
                    text: this.$trans('b2bPlatform.exampleDevices.messages.createSuccess')
                });

                this.$router.push('/example-devices');
            }).catch(() => {
                this.isLoading = false;

                this.$notify({
                    type: 'error',
                    title: this.$trans('b2bPlatform.messages.errorTitle'),
                    text: this.$trans('b2bPlatform.exampleDevices.messages.createFailed')
                });
            })
        }
    }
});
