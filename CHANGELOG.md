# Changelog

All notable changes to this project will be documented in this file. See [commit-and-tag-version](https://github.com/absolute-version/commit-and-tag-version) for commit guidelines.

## 0.0.1 (2024-01-27)


### Features

* **auth:** add routes auth (login, refresh, register and logout) with test ([5c56891](https://github.com/pedrosalpr/pedrosa-payments/commit/5c56891fd540e64436be67b98fb127927d0cb4a9))
* **config:** change configuration app, auth and database ([50342fb](https://github.com/pedrosalpr/pedrosa-payments/commit/50342fbbf6ddaf1c55581ffd375c72375d4a8ba6))
* **exception:** add exception returns http api problem ([cdd18fc](https://github.com/pedrosalpr/pedrosa-payments/commit/cdd18fcd7f8324ed9f6433166a307da006c72e42))
* **health:** add route and test feature of health ([9a328ec](https://github.com/pedrosalpr/pedrosa-payments/commit/9a328ec06a0e5f4059396b588cc97059477a42c3))
* **jwt:** add package and configuration jwt ([f4c89c5](https://github.com/pedrosalpr/pedrosa-payments/commit/f4c89c5b684c3c5ba9f7e5f68052d8299c29852e))
* **payment-get:** add route get payment ([3eae386](https://github.com/pedrosalpr/pedrosa-payments/commit/3eae386edef6fbca467cd4e6b9e8d7082cea8396))
* **payment-list:** add route to list payments ([f5317fb](https://github.com/pedrosalpr/pedrosa-payments/commit/f5317fbf1d64e4c665de9d6ac3c1fb427977f91d))
* **payment-process:** process payment ([97288b3](https://github.com/pedrosalpr/pedrosa-payments/commit/97288b3dc7e4a42f4ce7cc2ce21a9e404b106aa7))
* **payment:** register payment ([e2d98f3](https://github.com/pedrosalpr/pedrosa-payments/commit/e2d98f3bb17a70be90b66062125d761c90ee8eed))
* **user-balance:** add route balance user ([c817fb3](https://github.com/pedrosalpr/pedrosa-payments/commit/c817fb36d71da88c789db111b53639cf076d67f4))
* **user:** add field cpf on user model ([12eb58a](https://github.com/pedrosalpr/pedrosa-payments/commit/12eb58a967da9dfe1338bcba5699850fe9935e4d))
* **user:** add jwt in model user ([466312f](https://github.com/pedrosalpr/pedrosa-payments/commit/466312f1cb09b182d955c084be79a96edc8fd3da))
* **user:** add softdeletes in migration and model user ([57e1ddd](https://github.com/pedrosalpr/pedrosa-payments/commit/57e1dddce358b0596369430bcc46a57056c10702))
* **user:** change column id autonumeric for uuid ([88508bc](https://github.com/pedrosalpr/pedrosa-payments/commit/88508bcc994e1d97e2cfc03d6c451658d0f2be77))


### Chore

* **.env.example:** change environments variables in example ([63e3a03](https://github.com/pedrosalpr/pedrosa-payments/commit/63e3a03f53b5c34ce7116829ad3d299752ee6ac8))
* **.gitignore:** add new directories in .gitignore ([bf49910](https://github.com/pedrosalpr/pedrosa-payments/commit/bf4991031a99bc2ba0e7831e3695c828fa289e9e))
* **commit:** add configurations version, commitlint and commit tag and version updater ([53f3863](https://github.com/pedrosalpr/pedrosa-payments/commit/53f38637160e357c6c8b8f86c67813a6d4526b06))
* **makefile:** add commands to facilitate the development with makefile ([d52ec75](https://github.com/pedrosalpr/pedrosa-payments/commit/d52ec7505b6d344959a3fd76437aba96ec237b2d))
* **phpunit:** change db connection in configuration phpunit.xml ([2b49836](https://github.com/pedrosalpr/pedrosa-payments/commit/2b49836379d95e6f394780f8e74c2bdc0b28d679))


### Build

* **composer.json:** add new packages, update packages and add scripts ([65ba7dd](https://github.com/pedrosalpr/pedrosa-payments/commit/65ba7dd5225bd5100bfc437952cf035b68aec07a))
* **composer.json:** add package activitylog with configuration and migration ([3422d5b](https://github.com/pedrosalpr/pedrosa-payments/commit/3422d5bdb6ecb689b7350b8ee90a2c732176d28c))
* **composer.json:** add package phppro/api-problem ([3106c6c](https://github.com/pedrosalpr/pedrosa-payments/commit/3106c6cc127f6908c63ce2dda60ab1a6bd564672))
* **composer.json:** add package validator-docs ([06b83dd](https://github.com/pedrosalpr/pedrosa-payments/commit/06b83dd185a945670a8fa303486c396af89c9bc7))
* **composer.json:** fix version to 0.0.0 and add keywords ([bda0715](https://github.com/pedrosalpr/pedrosa-payments/commit/bda0715870b5640266011f18d0bcc2845d27159c))
* **docker-compose:** add docker-compose and change name of database ([3979f5d](https://github.com/pedrosalpr/pedrosa-payments/commit/3979f5db51f07e804c005dc950465a539ef855a7))
* **husky:** add configuration hooks commit-msg, pre-commit, prepare-commit-msg ([5e773f5](https://github.com/pedrosalpr/pedrosa-payments/commit/5e773f58fb025d0e56d45373660f9646e023af5f))
* **package.json:** add new packages commitlint, husky, validate branch name, git convetional ([898cbbf](https://github.com/pedrosalpr/pedrosa-payments/commit/898cbbfc5cd02f186cb6fb118fcb1fec9eba9709))
* **package.json:** add redocly, prism, spectral and scripts for documentation api ([262aca0](https://github.com/pedrosalpr/pedrosa-payments/commit/262aca083e217076e18aa94a37c8eb806f1721a6))
* **php-cs-fixer:** add configuration php-cs-fixer ([2225031](https://github.com/pedrosalpr/pedrosa-payments/commit/22250315891268805c5eb7c50711fb1a31662c42))
* **setup:** add php (dockerfile, php.ini), nginx (sites-available, bad-user), mysql and entrypoint ([bd0fab1](https://github.com/pedrosalpr/pedrosa-payments/commit/bd0fab14846283fb903fa0c4db115ad2746762d7))


### Docs

* **api:** add spec openapi and page swagger ([9cb67a2](https://github.com/pedrosalpr/pedrosa-payments/commit/9cb67a2e7c872d50b38bd4f13cccf0c348ffc49f))
* **readme:** update readme and add image state diagram ([c327209](https://github.com/pedrosalpr/pedrosa-payments/commit/c327209c6b9a419eeaea899b61966994c97dd834))


### Refactor

* **test:** fix namespace auth test ([a1986c8](https://github.com/pedrosalpr/pedrosa-payments/commit/a1986c863d1dcf6e4f186593c32fd3d224f9db67))


### Test

* **feture-test:** add new tests feature auth, payments and user ([54dcacf](https://github.com/pedrosalpr/pedrosa-payments/commit/54dcacf189483c90ead95b2bd0f4bda0fe63fef8))
* **payment-process:** add test for forbidden when try payment other user ([c44d2de](https://github.com/pedrosalpr/pedrosa-payments/commit/c44d2dedb47387912cd61e244ad025e0567c3cf7))
