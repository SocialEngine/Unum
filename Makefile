SOCIALENGINE_STRD="`pwd`/vendor/socialengine/sniffer-rules/src/Socialengine/SnifferRules/Standard/"
PHPCS=php ./vendor/bin/phpcs
INSTALLED=$(shell ${PHPCS} -i)

test:
	php ./vendor/bin/phpunit

test-cover:
	php ./vendor/bin/phpunit --coverage-clover=coverage.clover

sniff: check-standard
	${PHPCS} --colors --standard=SocialEngine --runtime-set allowSnakeCaseMethodName '[{"classSuffix":"Test","methodPrefix":["test"]}]' src tests

sniff-fix: check-standard
	php ./vendor/bin/phpcbf --colors --standard=SocialEngine --runtime-set allowSnakeCaseMethodName '[{"classSuffix":"Test","methodPrefix":["test"]}]' src tests

check-standard:
ifeq (,$(findstring SocialEngine, $(INSTALLED)))
	php ./vendor/bin/phpcs --config-set installed_paths ${SOCIALENGINE_STRD}
endif
