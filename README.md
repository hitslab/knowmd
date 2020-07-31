# KnowMD

Static site generator base on markdown for writing technical documentation or knowledge base.

## Prepare your documents

Use one folder for writing documents and another for asset files, for example:

```
.
├── docs
│   ├── README.md
│   ├── section1
│   │   └── README.md
│   └── article.md
│
└── assets
    ├── documentation.pdf
    └── image.jpg
```

## Generate static website

```sh
docker run --rm \
        -v $PWD/docs:/project/docs \
        -v $PWD/assets:/project/assets \
        -v $PWD/build:/project/build \
    hitslab/knowmd
```

As a result, you will get a static site in `build` folder:

```
.
└── build
    ├── documentation.pdf
    ├── image.jpg
    ├── index.html
    ├── section1
    │   └── index.html
    └── article.html
```

Markdown was rendered to html, assets files was moved to root directory, `README.md` was renamed to `index.html`.

## Templates

If you want to use your own html template, create file `page.html`:

```
.
└── templates
    └── page.html
```

Add tokens `{sidebar}` and `{document}` to body, they will be replaced with rendered content:

```html
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My documentation</title>
</head>
<body>
<div class="sidebar">{sidebar}</div>
<div class="content">{document}</div>
</body>
</html>
```

Add `templates` volume to docker command:

```sh
docker run --rm \
        -v $PWD/docs:/project/docs \
        -v $PWD/assets:/project/assets \
        -v $PWD/build:/project/build \
        -v $PWD/templates:/project/templates \
    hitslab/knowmd
```
