Ext.onReady(function()
{
    if (Ext.get('js-grid-placeholder'))
    {
        var urlParam = intelli.urlVal('status');

        intelli.announcements =
        {
            columns: [
                {name: 'title', title: _t('title'), width: 2, editor: 'text'},
                {name: 'date_added', title: _t('date'), width: 200, editor: 'date'},
                {name: 'date_expire', title: _t('date_expire'), width: 200, editor: 'date'},
                'status',
                'update',
                'delete',
                'selection'
            ],
            sorters: [{property: 'date_expire', direction: 'DESC'}],
            storeParams: urlParam ? {status: urlParam} : null,
            url: intelli.config.admin_url + '/announcements/'
        };

        intelli.announcements = new IntelliGrid(intelli.announcements, false);
        intelli.announcements.toolbar = Ext.create('Ext.Toolbar', {items:[
        {
            emptyText: _t('text'),
            name: 'title',
            listeners: intelli.gridHelper.listener.specialKey,
            width: 275,
            xtype: 'textfield'
        }, {
            displayField: 'title',
            editable: false,
            emptyText: _t('status'),
            id: 'fltStatus',
            name: 'status',
            store: intelli.announcements.stores.statuses,
            typeAhead: true,
            valueField: 'value',
            xtype: 'combo'
        }, {
            handler: function(){intelli.gridHelper.search(intelli.announcements);},
            id: 'fltBtn',
            text: '<i class="i-search"></i> ' + _t('search')
        }, {
            handler: function(){intelli.gridHelper.search(intelli.announcements, true);},
            text: '<i class="i-close"></i> ' + _t('reset')
        }]});

        if (urlParam)
        {
            Ext.getCmp('fltStatus').setValue(urlParam);
        }

        intelli.announcements.init();
    }
});