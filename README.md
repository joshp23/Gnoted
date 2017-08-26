Gnoted
======
A secure PHP based Web App for reading either Gnote or Tomboy Notes.

### Features
- Easily switch between Bootstrap [Bootswatch](https://bootswatch.com/) themes
- Sort Notes by Notebook or view full list
- Optional password protection: single user or give others access

### SETUP

1. Copy the Gnoted main folder (this repository) to your web server. See the provided Apache2 alias example.
2. Rename "config.php.dist" to "config.php" and set the values, if necessary 
3. Connect Tomboy Notes' or Gnote's note folder:  
	a. Symlink:

	For Gnote this will be ` ~/.local/share/gnote `  
	For Tomboy this will be ` ~/.local/share/tomboy `

	```bash
	$ cd /path/to/gnoted
	$ ln -s ~/.local/share/gnote notes
	```

	b. Alter the $APP_PATH setting from "notes" to "/home/YOUR_USER_NAME/.local/share/gnote" (or tomboy, respectively).

4. Set permissions  
	a. Make certain that www-data is a member of the YOUR_USERNAME group, and then check your work by executing:
	
	```bash
	$ usermod -a -G YOUR_USERNAME www-data
	$ groups www-data
	```

	b. Make certain that 750 permissions are set on  ` ~./.local`  ` ~./.local/share` and ` ./.local/share/gnote`
 
	```bash
	$ chmod 750 ~./.local
	$ chmod 750 ~./.local/share
	$ chmod 750 ~./.local/share/gnote -R
	```

### CREDITS
Scripts used for inspiration and/or copypasta:

1. https://wiki.gnome.org/Apps/Tomboy/UsageIdeas

2. https://github.com/gojigeje/tomboy-php

3. http://www.zubrag.com/scripts/

===========================

    Copyright (C) 2017 Josh Panter

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
