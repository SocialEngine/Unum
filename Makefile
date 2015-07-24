test:
	php ./vendor/bin/phpunit

test-cover:
	php ./vendor/bin/phpunit --coverage-clover=coverage.clover

sniff:
	php ./vendor/bin/phpcs --colors --standard=SocialEngine --runtime-set allowSnakeCaseMethodName '[{"classSuffix":"Test","methodPrefix":["test"]}]' src tests

sniff-fix:
	php ./vendor/bin/phpcbf --colors --standard='./vendor/socialengine/sniffer-rules/src/Socialengine/SnifferRules/Standard/SocialEngine' --runtime-set allowSnakeCaseMethodName '[{"classSuffix":"Test","methodPrefix":["test"]}]' src tests

