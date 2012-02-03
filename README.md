# Hoopa

hoopa is a cli script to write, edit and publish blog posts from your terminal and your text editor.
It supports blog plateforms implementing metaWebblog xml/rpc API (wordpress and dotclear for example).

Concept inspiration from http://www.la-grange.net/2011/07/30/something and adapted to my needs.

# How to use it

Help is available from: ./hoopa help

## Commands

./hoopa idea [name] -> create a file named [name] and open it in your favorite editor.  
./hoopa edit [name] -> open an existing note named [name] in your favorite editor.  
./hoopa publish [name] -> push and publish the note named [name]. update it if it already exists.  
./hoopa ideas -> list the note you have on your file system.  
./hoopa cats -> list the blog categories from your blog.  


## Configuration

Create a config file under ~/.config/hoopa/hoopa.ini

rpc_url = ""  
blog_id = ""  
username = ""  
password = ""  
editor = "sublime-text-2" (or whatever your favorite editor is)  