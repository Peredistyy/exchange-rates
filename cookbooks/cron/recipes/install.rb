template "/etc/crontab" do
    source "crontab.erb"
    owner "root"
    group "root"
    mode 0644
end

service "crond" do
    action :restart
end