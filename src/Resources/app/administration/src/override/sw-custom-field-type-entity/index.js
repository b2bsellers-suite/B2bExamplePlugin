
const {Component} = Shopware;

Component.override('sw-custom-field-type-entity', {
    computed: {
        entityTypes() {
            const parent = this.$super('entityTypes');

            return parent.concat([{
                label: this.$tc('sw-settings-custom-field.customField.entity.b2bsellers_example_devices'),
                value: 'b2bsellers_example_devices',
                config: {
                    labelProperty: 'name',
                },
            }
            ]);
        }
    }
});