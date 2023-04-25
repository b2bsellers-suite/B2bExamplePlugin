import deDE from './snippet/de_DE.json';
import enGB from './snippet/en_GB.json';
import './override/sw-custom-field-type-entity';
import './override/customFieldDataProviderService';

Shopware.Locale.extend('de-DE', deDE);
Shopware.Locale.extend('en-GB', enGB);


