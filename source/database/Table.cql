/* AVATAR TABLE:
 * 
 * --- NOTE: 
 * 		status:
 * 			10: default avatar for male
 * 			11: default avatar for female
 * --- Default data (default avatar for new user): 
 * 				insert into avatar(user_id, image_id, status) values (host_male, host_male_10, 10) 
 * 				insert into avatar(user_id, image_id, status) values (host_female, host_female_11, 11)
 * */
 
 /* THUMBNAIL TABLE:
  * 
  * --- NOTE:
   * 	 type:
  			1: doc
			2: docx
			3: txt
   			4: xls
			5: js
			6: pdf
			7: jpg, jpeg
			8: png
			9: bmp
			10: ic
			11: html, htm
			12: css
			13: php
   			14: default 
   
 * 				
 * 				
  */
/*
	NOTE for "status" column: 
		1: new
		0: current
		-1: old
*/

create keyspace DriveWeb with replication = {'class':'SimpleStrategy','replication_factor':2};

// Store avatar for users
create table avatar(
	user_id uuid,
	image_id uuid,
	status int,
	primary key((user_id), image_id)
);

//drop table avatar;

//Store infomation for each file
create table file_info(
	user_id uuid,
	file_id uuid,
	name text,
	size bigint,
	date_modify timestamp,
	description text,
	type int,
	status int,
	primary key(user_id, file_id)
);

// Store content for each file
create table file_content(
	file_id uuid,
	part int,
	content blob,
	primary key(file_id, part)
);

// Store number of chunk for each file
create table count_content(
	file_id uuid,
	count counter,
	primary key(file_id)
);

// Store thumbnail for each file and image
create table thumbnail(
	file_id uuid,
	id uuid,
	type int,
	image blob,
	status int,
	primary key((file_id), id)
);

// store folder list
create table folder(
	folder_id uuid,
	file_id uuid,
	primary key (folder_id, file_id)
);

//store infomation of each folder
create table folder_info(
	user_id uuid,
	folder_id uuid,
	date_modify timestamp,
	name text,
	size bigint,
	description text,
	status int,
	primary key (user_id, folder_id)
);