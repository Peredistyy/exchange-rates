ENV['VAGRANT_DEFAULT_PROVIDER'] = 'virtualbox'
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
    config.ssh.forward_agent = true
    config.vm.box = "chef/centos-6.5"
    config.vm.provision "shell", path: "scripts/install_chef.bash"
    config.vm.network "private_network", ip: "192.168.100.100"
    config.vm.synced_folder "./application", "/var/www/application"

    config.vm.provision :chef_solo do |chef|
        chef.cookbooks_path = "cookbooks"
        chef.roles_path = "roles"
        chef.add_role("prod")
    end
end
