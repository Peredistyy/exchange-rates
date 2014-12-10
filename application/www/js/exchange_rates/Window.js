Ext.define(
    "ExchangeRates.Window",
    {
        extend: "Ext.Window",
        title: "Курсы валют",
        closable: false,
        autoHeight: true,
        width: 800,
        border: true,
        initComponent: function () {
            var me = this;

            me.items = [
                Ext.create("ExchangeRates.window.Grid", me.config["grid"])
            ];

            me.callParent(arguments);
        }
    }
);