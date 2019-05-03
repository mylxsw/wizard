
build:
	docker build -t mylxsw/wizard .

package:
	tar -zcf wizard.package.tar.gz * .[!.]*
