## Unit tests
The tests are structured to be somewhat progressive and to be executed in a specific order as the earlier tests require less of the overall infrastructure of the package.

The order is as follows:

| Test   | Description      |
|:------:|:----------------|
| location| Tests some functions of the Locator class. This class depends only on the config value of `data_root`.
| featured_image| Tests that the encoding used for describing a featured image can be successfully decoded into a path/url. The `FeaturedImage` class only depends on knowledge of the structure of an image `Gallery\Object`. Thus testing this object requires no infrastructure.
| props | Tests the creation cycle for model classes `Album`, `Article`, `Banner`, `Editorial`, `Entry` and `Post`. The cycle tested is, create a new HED file, read the HED file back and etst all the properties, create a model object from the HED object and again test the properties. The props test only require the `Locator` class. |
| db | Tests construction, and structure, of the sql database. | 
| models | Repeats some of the `props` test but is more focused on interaction with sql. The core models of `Album`, `Article`, `Banner`, `Editorial`, `Entry` are tested for inser, delete, get, find. In addition the models `ArticleTitle`, `Category`, `CategorizedItem`, `EntryCountry`, `EntryLocation`, `NextPrev`, and `PostMonth` are tested. This set of tests require a full functional sql database to be built.