# lolcommits
A Slack integration + client-side GIT post-commit hook that takes a webcam picture, overlays the commit message and posts this in a Slack channel of ours.

# Getting started if you work at Socialsquare
Download the lol-post-commit hook by navigating your browser to
http://socialsquare.dk/lolcommits - now place this in your home folder, make
sure you add execute permissions

	chmod u+x ~/lol-post-commit

to the file and finally symbolic link it into the .git/hooks folders of any
project that you want to activate lol commits for.

	ln -s ~/lol-post-commit .git/hooks

