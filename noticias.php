<?php
$rss = simplexml_load_file("https://news.google.com/rss/search?q=industria+petrolera+m%C3%A9xico&hl=es-419&gl=MX&ceid=MX:es");

if ($rss && isset($rss->channel->item)) {
    // Forzar que $items sea siempre un array, incluso si solo hay 1 noticia
    $items = is_array($rss->channel->item) ? $rss->channel->item : iterator_to_array($rss->channel->item);

    // Tomar máximo 3 noticias
    $items = array_slice($items, 0, 5);

    foreach ($items as $item) {
        $desc = $item->description;
        $img = '';

        if (preg_match('/<img.*?src=["\'](.*?)["\']/', $desc, $matches)) {
            $img = $matches[1];
        }

        echo "<div class='mb-10 border-b border-gray-300 pb-6 hover:bg-red-50 rounded-lg transition flex flex-col md:flex-row gap-6 items-center'>";
        
        if ($img) {
            echo "<div class='flex-shrink-0 w-full md:w-48 h-32 md:h-32 rounded-lg overflow-hidden shadow-md'>";
            echo "<img src='{$img}' alt='Imagen noticia' class='w-full h-full object-cover'>";
            echo "</div>";
        }

        echo "<div class='flex-1'>";
        echo "<a href='{$item->link}' target='_blank' class='block text-2xl font-bold text-red-700 hover:underline hover:text-red-900 transition duration-300'>{$item->title}</a>";
        echo "<p class='text-xs text-gray-500 mt-1 italic'>" . date("d/m/Y H:i", strtotime($item->pubDate)) . "</p>";
        echo "<p class='text-gray-700 mt-3 leading-relaxed'>" . strip_tags(substr($desc, 0, 250)) . "...</p>";
        echo "</div>";

        echo "</div>";
    }
} else {
    echo "<p class='text-gray-600'>No se pudieron cargar las noticias del sector en este momento.</p>";
}
?>
