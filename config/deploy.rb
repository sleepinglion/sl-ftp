set :application, 'sl-ftp'
set :repo_url, 'git@github.com:sleepinglion/sl-ftp.git'
set :branch, 'master'
# Default branch is :master
# ask :branch, `git rev-parse --abbrev-ref HEAD`.chomp

# Default deploy_to directory is /var/www/my_app_name
set :deploy_to, '/home/deploy/sl-ftp'

# Default value for :scm is :git
#set :scm, :git

# Default value for :format is :airbrussh.
# set :format, :airbrussh

# You can configure the Airbrussh format using :format_options.
# These are the defaults.

# Default value for :pty is false
set :pty, true

# Default value for :linked_files is []
append :linked_files, 'config/config.php'

# Default value for linked_dirs is []
append :linked_dirs, 'public/tmp'


namespace :deploy do
  after :starting, 'composer:install_executable'
end

# Default value for default_env is {}
# set :default_env, { path: "/opt/ruby/bin:$PATH" }

# Default value for keep_releases is 5
# set :keep_releases, 5
