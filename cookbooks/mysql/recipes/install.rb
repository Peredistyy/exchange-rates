package "mysql-server"  do
    action :install
end

service "mysqld" do
    action :start
end

mysql_db "mysql-server" do
    db_root_password node[:mysql][:root_password]
    action :set_root_password
end

node[:mysql][:database].each do | db_name, db_config |
    mysql_db db_name do
        db_name     db_name
        db_user     db_config["user"]
        db_password db_config["password"]
        action :create
    end
end