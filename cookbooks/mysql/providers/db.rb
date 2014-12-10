action :set_root_password do
    execute "set root password" do
        command "mysqladmin -u root password '#{new_resource.db_root_password}'"
        only_if "mysql -u root -e 'show databases;'"
    end
end

action :create do
    current_sql = [
        "CREATE DATABASE IF NOT EXISTS #{new_resource.db_name};",
        "GRANT USAGE ON *.* TO #{new_resource.db_user}@localhost IDENTIFIED BY \"#{new_resource.db_password}\";",
        "GRANT ALL PRIVILEGES ON #{new_resource.db_name}.* TO #{new_resource.db_user}@localhost;"
    ]

    current_sql.each do | sql |
        execute "mysql -u root -p#{node[:mysql][:root_password]} -e '#{sql}'"
    end
end