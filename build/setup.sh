if [ ! -f .git/hooks/pre-commit ]; then
  cp build/pre-commit .git/hooks/pre-commit
  chmod +x .git/hooks/pre-commit
fi

if [ ! -f phpunit.xml ]; then
  cp build/phpunit.xml.dist phpunit.xml
fi
