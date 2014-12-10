php_repository "add" do
    source "http://mirror.webtatic.com/yum/el6/latest.rpm"
end

[
    "php55w",
    "php55w-fpm",
    "php55w-mysql",
    "php55w-pdo",
    "php55w-devel",
    "php55w-common",
    "php55w-xml",
    "php55w-cli",
].each do |pkg|
    package pkg do
        action :install
    end
end

template "/etc/php-fpm.d/www.conf" do
    source "www.conf.erb"
    owner "root"
    group "root"
    mode 0644
end

service "php-fpm" do
    action :start
end