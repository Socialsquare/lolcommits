# lolcommits
A Slack integration + client-side GIT post-commit hook that takes a webcam picture, overlays the commit message and posts this in a Slack channel of ours.

# Getting started if you work at Socialsquare and you're on a Debian OS
Download the lol-post-commit hook by navigating your browser to
http://socialsquare.dk/lolcommits now place this in your home folder, make
sure you add execute permissions to the file

	chmod u+x ~/lol-post-commit

Make sure you have the mplayer dependency installed, by running

	apt-get install mplayer

If you're on a debian system. Update the configuration parameters in the script
to match your system and finally symbolic link it into the .git/hooks folders
of any project that you want to activate lol commits for, by cd'ing into them
and running

	ln -s ~/lol-post-commit .git/hooks/post-commit


# Getting started if you work at Socialsquare and you're on OSX
Download the bash script called: `lol-post-commit-ffmpeg`, and place it 
somewhere on your computer, where it's easy to find again. Then make
it executable:

	chmod u+x ~/lol-post-commit

Make sure you have the ffmpeg dependency installed, by running

	brew install ffmpeg

The final step, to make the script work with your current git project is
to symbolic link it into your git project by running, from the git
projects root.
OBS: The location of the script has to be defined as an 
absolute path, otherwise the symbolic link can't execute the script:

	ln -s /Users/someuser/lol-post-commit .git/hooks/post-commit

