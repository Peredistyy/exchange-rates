package "nginx12"  do
    action :upgrade
end

template "/etc/nginx/fastcgi_params" do
    source "fastcgi_params.erb"
    owner "root"
    group "root"
    mode 0644
end

template "/etc/nginx/nginx.conf" do
    source "nginx.conf.erb"
    owner "root"
    group "root"
    mode 0644
end

service "nginx" do
    action :start
end