import template from './template.html.twig';
const {Component, Mixin} = B2bPlatform;
const cloneDeep = require('lodash.clonedeep');
import {mapGetters} from "vuex";


Component.register('example-devices-detail', {
    template,

    inject: ['apiService', 'permissionService'],
    mixins: [Mixin.getMixin('compare-objects')],

    data() {
        return {
            isLoading: true,
            _initialData: null,
            data: null,
            isDetailPage: false
        }
    },

    computed: {
        ...mapGetters({
            'employee': ['context/employee']
        }),

        dataChanged() {
            if (!this._initialData) {
                return true;
            }
            return this.getChangedObjectAttributes(this.data, this._initialData) !== false;
        }
    },

    created() {
        this.createComponent();
        this.checkDetailPageRoute();
    },

    methods: {
        createComponent() {
            this.isLoading = true;

            Promise.all([
                this.apiService.get('example-devices/get/' + this.$route.params.id),
            ]).then((response) => {
                this.isLoading = false;
                this.data = response[0].data;
                this._initialData = cloneDeep(this.data);
            });
        },

        onFormSave() {
            this.$validator.validate().then(valid => {
                if (!valid) {
                    return;
                }

                this.save();
            });
        },

        save() {
            const data = this.getChangedObjectAttributes(this.data, this._initialData);

            if (data === false) {
                return;
            }

            this.isLoading = true;

            this.apiService.patch('example-devices/' + this.$route.params.id, data).then(() => {

                this.isLoading = false;

                this.$notify({
                    type: 'success',
                    title: this.$trans('b2bPlatform.messages.successTitle'),
                    text: this.$trans('b2bPlatform.exampleDevices.messages.updateSuccess')
                });

                this._initialData = cloneDeep(this.data);
            }).catch(() => {
                this.isLoading = false;

                this.$notify({
                    type: 'error',
                    title: this.$trans('b2bPlatform.messages.errorTitle'),
                    text: this.$trans('b2bPlatform.exampleDevices.messages.updateFailed')
                });
            })
        },

        checkDetailPageRoute() {
            if (this.$route.name === 'cost_center.detail') {
                this.isDetailPage = true
            }
        }
    }
});