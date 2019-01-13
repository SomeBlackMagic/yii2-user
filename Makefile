fix-permission:
	sudo chown -R $(shell whoami):$(shell whoami) *
	sudo chown -R $(shell whoami):$(shell whoami) .docker/
	find .docker/ -type f -print0 | sudo xargs -0 dos2unix
