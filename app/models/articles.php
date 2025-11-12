<?php

$LOREM = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean fringilla maximus sapien, eu venenatis sapien ultricies sed. Sed lacinia ipsum neque, vel ultricies massa vulputate ut. Donec dui justo, fringilla sed condimentum nec, fermentum at nisi. Praesent bibendum justo tristique, feugiat libero at, accumsan odio. Phasellus ultricies risus vitae augue sollicitudin consectetur. Cras tellus massa, fringilla non efficitur sed, rutrum molestie augue. Mauris nec enim leo.
Vestibulum laoreet est eget ligula elementum, eleifend pulvinar elit dignissim. In hac habitasse platea dictumst. Etiam dictum scelerisque ipsum sed consequat. Aenean non quam lorem. Quisque fringilla vehicula purus, porttitor viverra massa vulputate et. Etiam ultricies est non mi lacinia mollis. Quisque elementum convallis purus, tempor rutrum purus finibus vel. Etiam ipsum tortor, sodales ut egestas ut, accumsan et quam. Quisque vel lobortis enim. Quisque eleifend cursus justo, in dictum mauris tincidunt at.
Vivamus porttitor eros eu euismod bibendum. Aliquam vel scelerisque ex, at maximus massa. Etiam mattis arcu sed elit aliquet, commodo fermentum nulla scelerisque. Aliquam at diam tempor, faucibus diam eget, vestibulum leo. Integer pellentesque, enim non blandit scelerisque, nunc ante imperdiet orci, vel maximus neque felis eget nulla. Aenean orci mi, venenatis ac nisi ac, fringilla maximus eros. Maecenas non felis sed dolor euismod lobortis id et mauris. Integer convallis leo sollicitudin sodales ultrices. Aliquam dignissim, orci vel lacinia ornare, magna arcu luctus libero, blandit convallis diam dolor id turpis. Quisque vitae sapien mauris. Donec fermentum vehicula bibendum. Nullam fermentum hendrerit dictum. Nullam viverra a ex id tempus.
Mauris quis egestas sapien. Nulla congue eu ipsum at malesuada. Phasellus auctor pharetra turpis, sollicitudin commodo urna convallis et. Nullam vel urna vestibulum enim mollis efficitur sit amet et turpis. Donec sodales ultrices maximus. Integer varius placerat luctus. Etiam sed ultricies purus. Nulla facilisi. Praesent nec rhoncus libero. Curabitur sem velit, accumsan vel blandit eu, feugiat nec arcu. Vestibulum sagittis bibendum leo, efficitur fringilla libero dignissim at. Donec vel neque laoreet, finibus lectus non, tincidunt lorem.
Morbi tincidunt ullamcorper tortor eget sodales. Cras ut eros blandit, finibus nisl in, faucibus tortor. Nullam nec blandit elit. Etiam id ex eget nisl imperdiet rhoncus. Maecenas rutrum tincidunt purus, a pellentesque ex efficitur vel. Curabitur convallis porttitor nunc, et vulputate leo vestibulum et. Morbi orci nulla, mollis eu faucibus sed, sagittis porta dolor. Maecenas interdum consequat libero sed interdum. Suspendisse ut leo id nisi mattis auctor. Cras efficitur justo id elit suscipit, dictum vehicula orci venenatis.";

$ARTICLES = [
  [
      "id" => "article-1",
      "imageUrl" =>
        "https://media.istockphoto.com/id/1147544807/vector/thumbnail-image-vector-graphic.jpg?s=612x612&w=0&k=20&c=rnCKVbdxqkjlcs3xH87-9gocETqpspHFXu5dIGB4wuM=",
      "titre" => "Article 1",
      "date" => "01/01/2025",
      "resume" => "Resumé de l'article 1",
      "contenu" => $LOREM,
  ],
  [
      "id" => "article-2",
      "imageUrl" =>
        "https://media.istockphoto.com/id/1147544807/vector/thumbnail-image-vector-graphic.jpg?s=612x612&w=0&k=20&c=rnCKVbdxqkjlcs3xH87-9gocETqpspHFXu5dIGB4wuM=",
      "titre" => "Article 2",
      "date" => "01/02/2025",
      "resume" => "Resumé de l'article 2",
      "contenu" => $LOREM,
  ],
  [
      "id" => "article-3",
      "imageUrl" =>
        "https://media.istockphoto.com/id/1147544807/vector/thumbnail-image-vector-graphic.jpg?s=612x612&w=0&k=20&c=rnCKVbdxqkjlcs3xH87-9gocETqpspHFXu5dIGB4wuM=",
      "titre" => "Article 3",
      "date" => "01/03/2025",
      "resume" => "Resumé de l'article 3",
      "contenu" => $LOREM,
  ],
  [
      "id" => "article-4",
      "imageUrl" =>
        "https://media.istockphoto.com/id/1147544807/vector/thumbnail-image-vector-graphic.jpg?s=612x612&w=0&k=20&c=rnCKVbdxqkjlcs3xH87-9gocETqpspHFXu5dIGB4wuM=",
      "titre" => "Article 4",
      "date" => "12/07/2008",
      "resume" => "Resumé de l'article 4",
      "contenu" => $LOREM,
  ],
];

class Articles {
  public function __construct() {}

  public function getArticles() {
    global $ARTICLES;
    $articles = ["articles" => $ARTICLES];
    return $articles;
  }

  public function getArticle(string $id) {
    global $ARTICLES;

    foreach ($ARTICLES as $article) {
        if ($article["id"] == $id) return $article;
    }

    return null;
  }
}
