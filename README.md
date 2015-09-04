# lolcommits
A Slack integration + client-side GIT post-commit hook that takes a webcam picture, overlays the commit message and posts this in a Slack channel of ours.

# Getting started if you work at Socialsquare and you're on a Debian OS
Download the lol-post-commit hook by navigating your browser to
http://socialsquare.dk/lolcommits now place this in your home folder, make
sure you add execute permissions to the file

	chmod u+x ~/lol-post-commit

make sure you have the mplayer dependency installed, by running

	apt-get install mplayer

if you're on a debian system. Update the configuration parameters in the script
to match your system and finally symbolic link it into the .git/hooks folders
of any project that you want to activate lol commits for, by cd'ing into them
and running

	ln -s ~/lol-post-commit .git/hooks

