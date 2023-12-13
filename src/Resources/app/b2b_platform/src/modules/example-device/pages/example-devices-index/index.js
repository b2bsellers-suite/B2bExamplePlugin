import template from './template.html.twig';
import {mapGetters} from "vuex";
import moment from "moment";

const {Component} = B2bPlatform;

Component.register('example-devices-index', {
    template,

    inject: ['apiService', 'permissionService'],

    data() {
        return {
            isLoading: false,
            columns: [],
            data: [],
            options: {
                showActions: true, actions: {
                    width: '115px',
                }
            },
            totalRows: 0,
            exampleDevicesToRemove: null,
            removePromptOpen: false,
            promptIsLoading: false,
            totalOrders: null
        }
    },

    computed: {
        ...mapGetters({
            'employee': ['context/employee'],
            'customer': ['context/customer']
        }),
    },

    created() {
        this.createColumns();
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.isLoading = true;

            Promise.all([this.onQueryChange()]).then((response) => {
                response[0].data.elements.forEach(exampleDevices => {
                    this.data.push(exampleDevices)
                })
                this.totalRows = response[0].data.total;
                this.isLoading = false;
            });
        },

        createColumns() {
            this.columns = [{
                title: this.$trans('b2bPlatform.exampleDevices.tableColumns.name'),
                key: 'name',
                field: 'name'
            }, {
                title: this.$trans('b2bPlatform.exampleDevices.tableColumns.description'),
                key: 'description',
                field: 'description',
            }, {
                title: this.$trans('b2bPlatform.exampleDevices.tableColumns.employee'),
                key: 'employee',
                field: 'employee',
                fieldRenderer: (column, row) => {
                    if (!row) {
                        return "";
                    }
                    return (row.employee.title || "")
                    + ' '
                    + row.employee.firstName
                    + ' '
                    + row.employee.lastName
                },
            },{
                title: this.$trans('b2bPlatform.exampleDevices.tableColumns.serialNumber'),
                key: 'serialNumber',
                field: 'serialNumber',
                fieldRenderer: (column, row) => {
                    return row.serial_number;
                }
            }, {
                title: this.$trans('b2bPlatform.exampleDevices.tableColumns.startAt'),
                key: 'startAt',
                field: 'startAt',
                fieldRenderer: (column, row) => {
                    return this.formatDate(row.start_at);
                }
            }, {
                title: this.$trans('b2bPlatform.exampleDevices.tableColumns.endAt'),
                key: 'endAt',
                field: 'endAt',
                fieldRenderer: (column, row) => {
                    return this.formatDate(row.end_at);
                }
            }];
        },

        formatName(firstName, lastName) {
            return firstName + " " + lastName;
        },

        onQueryChange() {
            return this.apiService.get('example-devices/list');
        },

        formatDate(value) {
            return moment(value).format('DD.MM.YYYY');
        },

        openRemovePrompt(exampleDevices) {
            this.exampleDevicesToRemove = exampleDevices;
            this.removePromptOpen = true;
        },

        onCloseDeletePrompt() {
            this.exampleDevicesToRemove = null;
            this.removePromptOpen = false;
        },

        onAcceptDeletePrompt() {
            this.promptIsLoading = true;

            this.apiService.delete('example-devices/' + this.exampleDevicesToRemove.id)
                .then(() => {
                    this.onCloseDeletePrompt();
                    this.promptIsLoading = false;
                    this.$refs.dataTable.reload();


                    this.$notify({
                        type: 'success',
                        title: this.$trans('b2bPlatform.messages.successTitle'),
                        text: this.$trans('b2bPlatform.exampleDevices.messages.deleteSuccess')
                    });
                }).catch(() => {
                this.onCloseDeletePrompt();
                this.promptIsLoading = false;
                this.$refs.dataTable.reload();
                this.$notify({
                    type: 'error',
                    title: this.$trans('b2bPlatform.messages.errorTitle'),
                    text: this.$trans('b2bPlatform.exampleDevices.messages.deleteFailed')
                });
            });
        }
    }
});