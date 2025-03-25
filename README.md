# CitizenZ 2025 avec Tailwindcss
Ce projet est une adaptation de CitizenZ 2025 pour Symfony 7.2 et Tailwindcss 4.

### Lancer les serveurs
```bash
symfony serve
```

```bash
symfony console tailwind:build --watch
```

### ENVIRONNEMENT
* Symfony 7.2
* PHP 8.3+
* Composer 2
* AssetMapper
* Tailwindcss 4

### BUNDLES
* easycorp/easyadmin-bundle
* symfony/asset-mapper
* knplabs/knp-paginator-bundle
* knplabs/knp-time-bundle
* karser/karser-recaptcha3-bundle
* symfonycasts/tailwind-bundle
* twig/intl-extra

### Fonts
* Poppins

### Mise en production
`php bin/console tailwind:build --minify`

`php bin/console asset-map:compile`