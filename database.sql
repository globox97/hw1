create table users(id integer AUTO_INCREMENT, username varchar(30), email varchar(100), password varchar(255), primary key(id, username));
create index i_user on users(username);
create table favorites(nickname varchar(30), id_fav varchar(255), type_fav varchar(20), primary key(nickname, id_fav, type_fav), foreign key(nickname) references users(username) on delete cascade);
create trigger 