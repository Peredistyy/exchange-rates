Vagrant PHP-server
==================

Инструкция по запуску
---------------------

Что необходимо установить?:

- [Vagrant](http://www.vagrantup.com/ "vagrant") версия ``1.6.3`` потому что версия ``1.6.4`` с багом,
а именно не прокидываеться приватный ip-адрес виртуальной машины
- [Virtualbox](https://www.virtualbox.org/ "virtualbox")

Процесс настройки:

* ``git clone https://github.com/Peredistyy/exchange-rates.git``
* ``cd exchange-rates``
* ``vagrant up``
* Проект станет доступным по адресу http://192.168.100.100/