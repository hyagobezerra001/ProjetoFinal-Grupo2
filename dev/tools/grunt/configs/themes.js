/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
 'use strict';
 /**
  * Define Themes
  *
  * area: area, one of (frontend|adminhtml|doc),
  * name: theme name in format Vendor/theme-name,
  * locale: locale,
  * files: [
  * 'css/styles-m',
  * 'css/styles-l'
  * ],
  * dsl: dynamic stylesheet language (less|sass)
  *
  */
 module.exports = {
    
     themewine: {
         area: 'frontend',
         name: 'Webjump/theme-wine',
         locale: 'pt_BR',
         files: [
             'css/styles-m',
             'css/styles-l'
         ],
         dsl: 'less'
     },
     themewine: {
        area: 'frontend',
        name: 'Webjump/theme-wine',
        locale: 'pt_BR',
        files: [
            'css/styles-m',
            'css/styles-l'
        ],
        dsl: 'less'
    },
    themefashion: {
        area: 'frontend',
        name: 'Webjump/theme-fashion',
        locale: 'pt_BR',
        files: [
            'css/styles-m',
            'css/styles-l'
        ],
        dsl: 'less'
    },
    themefashion_us: {
        area: 'frontend',
        name: 'Webjump/theme-fashion',
        locale: 'en_US',
        files: [
            'css/styles-m',
            'css/styles-l'
        ],
        dsl: 'less'
    }
     
 };

