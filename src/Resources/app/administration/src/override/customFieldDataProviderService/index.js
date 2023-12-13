const { Application, State } = Shopware;

const entities = [
    'b2bsellers_example_devices',
]

Application.addServiceProviderMiddleware('customFieldDataProviderService', (customFieldService, next) => {
    entities.forEach((value) => {
        if (!customFieldService.getEntityNames().includes(value)){
            customFieldService.addEntityName(value);
        }
    })

    next();
});