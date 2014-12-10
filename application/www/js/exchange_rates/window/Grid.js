Ext.define(
    "ExchangeRates.window.Grid",
    {
        extend: "Ext.grid.Panel",
        columnLines: true,
        initComponent: function () {
            var me = this;

            me.store = me.getStore();
            me.columns = me.getColumns();
            me.dockedItems = [
                me.getTopToolbar(),
                me.getBottomToolbar()
            ];

            me.callParent(arguments);
        },
        getStore: function () {
            var me = this;

            return {
                fields: [
                    {name: "id", type: "integer"},
                    {name: "currencyOf", type: "string"},
                    {name: "currencyIn", type: "string"},
                    {name: "rate", type: "string"}
                ],
                data: me.config["rates"]
            };
        },
        getColumns: function () {
            return [
                {
                    text: "Из валюты",
                    dataIndex: "currencyOf",
                    width: 300
                },
                {
                    text: "В валюту",
                    dataIndex: "currencyIn",
                    width: 300
                },
                {
                    text: "Курс",
                    dataIndex: "rate",
                    width: 100
                },
                {
                    xtype: "actioncolumn",
                    width: 85,
                    align: "center",
                    items: [
                        this.getRemoveButton()
                    ]
                }
            ];
        },
        getTopToolbar: function () {
            var me = this;

            return new Ext.Toolbar({
                dock: "top",
                itemId: "top_toolbar",
                items: [
                    me.getAddForm()
                ]
            });
        },
        getBottomToolbar: function () {
            var me = this;

            return new Ext.Toolbar({
                dock: "bottom",
                itemId: "bottom_toolbar",
                items: [
                    me.getRefreshButton()
                ]
            });
        },
        getAddForm: function () {
            var me = this;

            return new Ext.form.FormPanel(
                {
                    extend: "Ext.form.FormPanel",
                    buttonAlign : "left",
                    width: 800,
                    height: 35,
                    items: [
                        {
                            xtype: "fieldcontainer",
                            layout: "hbox",
                            defaults:
                            {
                                width: 300,
                                labelWidth: 70,
                                margin: "5 5 0 5"
                            },
                            items : [
                                {
                                    xtype: "combobox",
                                    id: "currencyOf",
                                    fieldLabel: "Из валюты",
                                    displayField: "name",
                                    editable: false,
                                    queryMode: "local",
                                    valueField: "id",
                                    store: {
                                        fields: ["id", "name"],
                                        data: me.config["currencies"]
                                    }
                                },
                                {
                                    xtype: "combobox",
                                    id: "currencyIn",
                                    fieldLabel: "В валюту",
                                    displayField: "name",
                                    editable: false,
                                    queryMode: "local",
                                    valueField: "id",
                                    store: {
                                        fields: ["id", "name"],
                                        data: me.config["currencies"]
                                    }
                                },
                                {
                                    xtype: "button",
                                    width: 150,
                                    formBind: true,
                                    text: "Добавить валюту",
                                    icon: "/images/add.gif",
                                    handler: function () {
                                        me.setLoading("Подождите...");

                                        var currencyOf = this.up("form").getForm().findField("currencyOf").getValue();
                                        var currencyIn = this.up("form").getForm().findField("currencyIn").getValue();

                                        if (currencyOf && currencyIn) {
                                            Ext.Ajax.request({
                                                url: "/currency/add/" + currencyOf + "/" + currencyIn,
                                                method: "POST",
                                                success: function () {
                                                    me.setLoading(false);
                                                    me.refreshAction();
                                                },
                                                failure: function () {
                                                    // TODO: Show error message
                                                }
                                            });
                                        }
                                    }
                                }
                            ]
                        }
                    ]
                }
            );
        },
        getRefreshButton: function () {
            var me = this;

            return {
                text: "Обновить курсы валют",
                icon: "/images/refresh.png",
                handler: me.refreshAction.bind(me)
            };
        },
        refreshAction: function () {
            var me = this;

            me.setLoading("Подождите...");
            Ext.Ajax.request({
                url: "/currency/list",
                method: "POST",
                params: {},
                success: function (response) {
                    var data = Ext.util.JSON.decode(response.responseText);
                    me.store.add(data["data"]);
                    me.setLoading(false);
                },
                failure: function () {
                    // TODO: Show error message
                }
            });
        },
        getRemoveButton: function () {
            var me = this;

            function removeRow(grid, rowIndex) {
                me.setLoading("Подождите...");

                Ext.Ajax.request({
                    url: "/currency/remove/" + grid.getStore().getAt(rowIndex).get("id"),
                    method: "POST",
                    params: {},
                    success: function () {
                        grid.getStore().removeAt(rowIndex);
                        me.setLoading(false);
                    },
                    failure: function () {
                        // TODO: Show error message
                    }
                });
            }

            return {
                icon: "/images/remove.png",
                handler: function (grid, rowIndex) {
                    Ext.MessageBox.show({
                        title: "Удалить",
                        msg: "Удалить?",
                        buttons: Ext.MessageBox.OKCANCEL,
                        icon: Ext.MessageBox.QUESTION,
                        fn: function (button) {
                            if (button == "ok") {
                                removeRow(grid, rowIndex);
                            } else {
                                return;
                            }
                        }
                    });
                }
            };
        }
    }
);