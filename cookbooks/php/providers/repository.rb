action :add do
    directory "/tmp/php" do
        owner "root"
        mode "0755"
        action :create
    end

    remote_file "/tmp/php/php5.rpm" do
        source "#{new_resource.source}"
        action :create_if_missing
    end

    rpm_package "php-package" do
        action :install
        source "/tmp/php/php5.rpm"
    end
end