# Curso Laravel 5 + Vue.JS

Link do [Curso Laravel 5 + VueJS](http://sites.code.education/laravel-com-vuejs/)

### Requisitos

 - [Laravel Homestead](https://laravel.com/docs/master/homestead)

### Instalação

No seu arquivo `homestead.yaml` configure:


```
sites:
    - map: laravue63.app 
      to: /home/vagrant/Code/Cursos/vue-laravel/public
      
ports:
    - send: 3000
      to: 3000
      
    - send: 3001
      to: 3001
```

Na sua pasta `~/Homestead`, baixe, instale e execute o projeto:

```sh
~$ cd ~/Homestead
~/Homestead$ vagrant up --provision
~/Homestead$ vagrant ssh
vagrant@homestead:~$ cd Code
vagrant@homestead:~/Code$ mkdir Cursos
vagrant@homestead:~/Code$ cd Cursos
vagrant@homestead:~/Code/Cursos$ git clone <repository> vue-laravel
vagrant@homestead:~/Code/Cursos$ cd vue-laravel
vagrant@homestead:~/Code/Cursos/vue-laravel$ npm install
vagrant@homestead:~/Code/Cursos/vue-laravel$ gulp
```
Configure seu arquivo `.env` com suas configurações de banco de dados e execute as migrations
```
vagrant@homestead:~/Code/Cursos/vue-laravel$ php artisan migrate
```
