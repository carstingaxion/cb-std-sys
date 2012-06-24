��    r      �  �   <      �	  "   �	  �  �	  -   z  5   �  C   �  ?   "  8   b  9   �  �   �     �  	   �  	   �  	   �  	   �  E   �               &  4  /     d     j     v  0  �  o   �     (     ?     X     s     �     �  8   �  ;   �  
        (  V   A     �     �     �  4   �  P   �     K  	   _     i  R   {  H   �  �     S   �  R  3  /   �  :   �     �       S        l  #   �     �  !   �  2   �  7   "  &   Z  "   �  �  �  �   {     �  1        I     U     s     �     �     �  
   �  2   �     �     �  .   q  4  �  �  �  x   q   E  �   �  0$  �  �%  O  �(  L   �,  ?   :-  "   z-  6   �-  �   �-  5   c.     �.     �.     �.  (   �.  !   /     #/     8/     N/     V/     g/  ;   �/     �/  0   �/     0  #   0     80     E0     ]0  -   y0     �0     �0     �0     �0     1  �  $1  )   �2  z  3  h   �5  H   6  i   K6  g   �6  \   7  b   z7  �  �7  	   �9  
   �9  
   �9  
   �9  
   �9  �   �9  	   D:  	   N:  	   X:  �  b:     =  #   =  /   ==  C  m=  �   �?  /   }@  1   �@  7   �@  "   A  &   :A     aA  �   �A  |   B     �B  3   �B  v   �B  ,   KC  
   xC  *   �C  p   �C  �   D  1   �D     �D  <   E  �   HE  �   F  &  �F  �   �G  a  oH  t   �K  �   FL  .   �L  2   *M  �   ]M  3   �M  4   N  .   IN  9   xN  d   �N     O  B   �O  L   �O  �  'P  �   T  0   �T  Q   0U     �U  '   �U     �U     �U     �U     V     !V  k   9V     �V  �   �V  L   cW  b  �W  d  Z  �   x_  �  H`    %f  �  -j  D  $o  �   iw  �   �w  %   �x  L   �x  ,  �x  �    z  /   �z     �z  "   {  [   ){  N   �{  C   �{  8   |     Q|  !   n|  ,   �|  M   �|  4   }  R   @}     �}  @   �}     �}  '   ~  0   ,~  E   ]~  )   �~  7   �~  1     C   7     {                   A   D   N   1   C                 7      X   <   '                                         )   d   T                e   G   c                  I   (       +   &      /   	   M                  h   k   V                     F       "   0   l   9      b   J   p   i   \   S   ]       Y   R   m       -   >           4   @   K   6      o   g   n       W           f   a      q       2   5   3   B   :   r   ,           ;   =      [       8      
   *      $   P   Z      #   ?      .   `   L   ^              E                   _   Q                    %   U   j   !          O   H     %1$s has an option line with %2$s "%s" attempts to provide some measure against the deletion of current "rss_hash" options by not deleting any "rss_hash" rows with an option_id newer than (the last row id - %d ). Because plugins and themes also add rows to the wp_options table, depending on your recent installation history, this may remove <strong>ALL</strong> of the "rss_hash" options, both older AND <strong>CURRENT</strong> ones, no questions asked. "all" means BOTH "plugin" AND "rss_" options. %s (the "rss_" Options query) did not return an array %s (the "rss_" non-timestamp Options query) did not return an array %s (the "rss_" timestamp Options query) did not return an array %s (the Orphaned Options query) did not return an array. %s (the review information query) did not return an array *Note* spaces have been added after every 10th character of the option_name and every 20th character of the option_value to preserve page layout.<br />Not all options have values and/or descriptions. 10 pairs 100 pairs 125 pairs 150 pairs 175 pairs 2 strings separated by %s (in uppercase, enclosed with asterisks) eg. 25 pairs 50 pairs 75 pairs A Warning message, means that something has happened and options that should not be deleted might be available for deletion, or options that could be safely deleted might not be available for deletion. In any case, if you see a Warning message, use extra-special care and thought before deleting any options. ALERT Age Unknown Alternate Syntax Although removing current "rss_hash" rows should not "break" your WordPress blog (they should be entered again next time the feed is cached), please <strong>BACKUP</strong> your database <strong>BEFORE</strong> doing this.<br />After using "%1$s", you should "%2$s" to clean the wp_options table further. Carefully Review information on the "View Selected Options Information" page <i>before</i> deleting the option. Could not open file %s Could not open folder %s Could not open folder/file Database Error Delete ALL 'rss' Options Deselect all Don't show the Alternate Syntax Warnings for this "Find" Don't show the Known WordPress Core options for this "Find" Empty Name Enter Search String here Every "rss_hash" option in the wp_options table will be shown, including current ones. File System Error Find Find Orphaned Options For comments / suggestions, please visit the blog %s For more information, the latest version, etc. please visit the plugin's page %s Further Information Google it High Memory usage If you have any questions, problems, comments, or suggestions, please let me know. If you would like to provide a translation, please leave a comment at %s It is <strong>highly recommended</strong> that you Limit the "Find" to only a selected number of the most recent "rss_hash" options Instead and repeat the process until the number becomes manageable. It is strongly suggested that you BACKUP your database before removing any options. Listed Options are those that are found in the wp_options table but are not referenced by "get_option" or "get_settings" by any of the PHP files located within your blog directory. If you have deactivated plugins and/or non-used themes in your directory, the associated options will not be considered orphaned until the files are removed. Look at the file names in any Warning messages. Look at the text in any Alternate Syntax Warning messages. Low Memory usage Moderate Memory usage Most likely there are an extreme number of "rss_hash" rows in the wp_options table. No Orphaned Options were found No Orphaned Options where selected. No Search string was entered. No files were found containing %s No, Don't remove them, return to the first screen. Note: all spaces are removed, search is case sensitive. Only WordPress Core Options were found Option with No Name with the value Perhaps some plugins/themes add options that have no name? Or the name becomes removed from the row somehow? Because this plugin finds options based on their names, these "no name" options will not be included in the list, and thus can not be selected for review or deletion. If the row has no option_name but has an option_value, it will be shown to help you identify the source of the problem. At present, if you wish to remove such rows you must do so by other means. Please review this information very carefully and only remove Options that you know for certain have been orphaned or deprecated. Possibly Orphaned Options Questions? For support, please visit the forum %s RSS Options Removed %d "rss_hash" Options Removed Options Return to the %s Search Search files by: Select all Some information may be available at your %s page. Submit The "rss_" options are obsolete as of WordPress version 2.8 All are selectable and it should be safe to remove any that remain. The Options table currently has %s found rows. The following Options appear to be orphans. When shown, non-selectable Options are known to have been created from files present during upgrade or backup, or are legitimate options that do not "fit" the search for get_option or get_settings. If you wish to remove them by other means, do so at your own risk. The following contains "RSS" Options added to the wp_options table from the blog's dashboard page and other files that parse RSS feeds and cache the results.<br />In each pair, the upper option is the cached feed and the lower is the option's timestamp.<br />Those listed may include options that are <strong>Currently Active</strong>.<br />When shown, "rss_" option pairs with dates newer or the same as the date of 14'th newest "rss_" option pair (the ones that are more likely to be current) have no checkbox but begin with "-" and end with "<em># %1$s</em>" in italics.<br />The older "rss_" options can be selected and end with "<strong># %2$s</strong>" in bold. The following options were not paired correctly. Be certain to check their information carefully before you remove them. The lower the number of "rss_hash" option pairs you "Find", the less likely it is that you will experience problems with memory during the "Find". However, depending on the number of feed rows that are current, the "Find" may not include any older ones that can be deleted.<br />The higher the number of "rss_hash" pairs you "Find", the more likely it is that older ones that can be deleted will be included. But there is a greater chance of having memory problems during the "Find".<br />It is suggested that you start off with a lower "Find", and increase the number gradually, if you wish to, on subsequent "Finds". If you get a memory error, use a lower number.<br />Again said, it is recommended that you scan the results on the review page of anything you select prior to it's deletion, to ensure that you really want to remove it. The plugin failed to open a folder/file. This is most often because of inadequate permissions settings. i.e. The "read" permission setting. They do not need to be "world" readable, but scripts must be able to. Options that are in files that can not be read may appear in the "orphaned options" list when in fact they are not orphaned. In many cases, knowing the folder/file should help in identification of options that are not really orphaned. The plugin queries the wp_options table. It expects an array with at least 1 row. This error message may be the result of fact. i.e. There actually are no wp_option rows with autoload=yes (next to impossible), or there actually are no "rss_hash" rows. Or it could be an actual database problem (eg. connection failure, memory failure). If you get this error message you should look for a WPDB error message as well for more detailed information. An error with either the autoload=yes query (core/plugin/theme options), or the autoload!=yes query (rss_hash options) means that none of the corresponding rows will be available for review or deletion until the database problem is resolved. The plugin searches PHP files for instances of get_option('option_name as a string') to match against values found in the wp_options table. Some files however use syntax such as get_option(&#36;variable) or get_option('prefix_' . &#36;variable). These option names will not match those found in the wp_option table, and they may be present in the list of Orphaned Options when in fact they are not really orphaned. In many cases, knowing the file, and the prefix if used, should help in identification of options that are not really orphaned.<br />*Note, if you get this warning with a plugin file (from the <u><i>WordPress.com Plugin Directory only</i></u>, please) and you know it's not a potential problem (eg. some WordPress core files, the WordPress.com Stats plugin, and this plugin have alternate syntax in them BUT <u><i>there are no options associated with them listed</i></u>), please visit the blog and leave a comment something like "the whatever plugin has alternate syntax but is OK" and I can add it to the "ignore" list for future version releases if it is safe to do so. Many Thanks. %s There is an autoload not equal to yes Option with No Name with the value: %s There is an autoload yes Option with No Name with the value: %s There were No "rss_" Options found These are the error messages as returned by WordPress. To avoid excessive memory use, using "%s" does not attempt to list all of the "rss_hash" options, nor will you be able to review the contents. To double-check options in the Orphaned Options list: To look for option_name(s): Tools Translation Acknowledgements Try a Google search for the option_name. View Selected Options Information WANTED - Bug Reports WANTED - Translations WARNING Warning Messages WordPress database error Yes, Remove ALL of these options from the wp_options table. all_or_portion_of_option_name check the age of the corresponding "_ts" option. days old entering either a single string eg. first screen has an option line with known WordPress Core option or a maximum of 3 strings separated by %s eg. prefix %s other_words prefix%1$sword%2$sother_word query did not return an array registration required to post was found in: Project-Id-Version: Clean Options 1.3.1
Report-Msgid-Bugs-To: 
POT-Creation-Date: 2010-03-01 22:21+0000
PO-Revision-Date: 2009-08-23 00:00+0000
Last-Translator: Vadim Nekhai <onix@onix.name>
Language-Team: Vadim Nekhai <onix@onix.name>
MIME-Version: 1.0
Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 8bit
X-Poedit-Language: Russian
X-Poedit-Country: RUSSIAN FEDERATION
X-Poedit-KeywordsList: __
X-Poedit-Basepath: .
X-Poedit-SearchPath-0: ..
  %1$s содержит опцию с %2$s "%s" пытается противостоять удалению текущих "rss_hash" опций, не удаляя "rss_hash" строки с option_id новее (id последней строки - %d). Поскольку плагины и темы также добавляют опции в таблицу wp_options, в зависимости от их истории недавних установок, это может удалить <strong>ВСЕ</strong> "rss_hash" опции, как старые, так и <strong>ТЕКУЩИЕ</strong> без всяческих вопросов и подтверждений. "все" означает и опции плагинов, и "rss_" опции одновременно. %s (запрос "rss_" опций) не возвратил массив %s (запрос "rss_" опций без указания даты) не возвратил массив %s (запрос "rss_" опций с указанием даты) не возвратил массив %s (запрос на осиротелые опции) не возвратил массив. %s (запрос на просмотр информации) не возвратил массив. *Примечание* пробелы должны быть добавлены через каждый десятый символ значения option_name и через каждый двадцатый значения option_value, чтобы сохранить разметку страницы в нормальном виде.<br />Не все опции имеют значения и/или описания. 10 пар 100 пар 125 пар 150 пар 175 пар двум строкам, разделенным %s (заглавные буквы выделены звездочками), например, 25 пар 50 пар 75 пар Предупреждения выдаются тогда, когда что-либо случилось и опции, которые не должны быть удалены, возможно, будут доступны для удаления, или наоборот: опции, которые необходимо удалить, не будут доступныим для данного действия. В любом случае, если вы видите на экране предупреждающие сообщения, уделите особое внимание списку опций перед тем, как будете что-либо удалять. ВНИМАНИЕ Возраст неизвестен Альтернативный синтаксис Хоть удаление  "rss_hash"  строк не должно  "сломать" блог WordPress (они снова появятся в следующий раз, когда движок будет кешировать новостную ленту), пожалуйста <strong>СДЕЛАЙТЕ РЕЗЕРВНУЮ КОПИЮ</strong> ваших данных <strong>ДО</strong> того, как сделать это.<br />После использования "%1$s", вы должны "%2$s", чтобы очистить таблицу wp_options в будущем. Внимательно проверьте информацию на странице "Просмотреть информацию о выбранных опциях" <i>перед</i> удалением. Невозможно открыть файл %s Невозможно открыть папку %s Невозможно открыть папку/файл Ошибка базы данных Удалить ВСЕ опции 'rss' Убрать выделения Не показывать предупреждения альтернативного синтаксиса для данного "Найти" Не показывать известные базовые опции ядра WordPress для данного "Найти" Пустое имя Введите запрос поиска здесь Все "rss_hash" опции таблицы wp_options будут отображены, включая текущие. Ошибка файловой системы Найти Найти осиротелые опции Для комментариев и предложений, пожалуйста, зайдите на сайт %s За дополнительной информацией, последней версией и т. д., обращайтесь на страницу плагина %s Дополнительная информация Погуглить Использовать максимально памяти Если у вас возникли какие-либо вопросы, проблемы, комментарии, идеи, пожалуйста, дайте мне об этом знать. Если вы хотите помочь нам с переводом, пожалуйста, оставьте комментарий здесь: %s <strong>Настоятельно рекомендуется</strong> ограничить поиск до меньшего числа новых опций "rss_hash", а затем повторять поиск снова до полной очистки БД от ненужных опций. НАСТОЯЕЛЬНО РЕКОМЕНДУЕТСЯ сделать резервную копию базы данных перед тем, как удалять что-либо. Показываемые опции характеризуют состояние таблицы wp_options вашей базы данных и не имеют никакого отношения к функциям "get_option" или "get_settings", описаных в PHP файлах, расположенных в каталоге вашего блога. Если вы деактивировали старые плагины и/или темы оформления, расположенные в папке движка, то вполне возможно, что опции, которые использовались этими расширениями, остались в базе данных, но они не будут считаться осиротелыми, пока файлы-родители не будут удалены с сервера. Проверьте имена файлов в любом из предупредительных сообщений. Проверьте предупредительные сообщения и сообщения, которые касаются альтернативного синтаксиса. Использовать мало памяти Использовать средне памяти Вполне вероятно, что в таблице wp_options находится очень много "rss_hash" опций. Не найдено осиротелых опций Не выбрано осиротелых опций. Не введено строки поиска. Не найдено файлов, содержащих %s Нет, не удалять ничего и вернуться к начальному экрану. Примечание: все пробелы будут удалены, поиск чувствителен к регистру. Были найдены только опции ядра WordPress Опция без имени, но содержащая информацию Возможно, некоторые плагины/темы создают опции, не имеющие названия? Или каким-то образом имя опции исчезает из базы данных? Так как этот плагин работает с опциями по ихним именам, подобные опции "без имени" не будут включены в список, и поэтому не смогут просматриваться или удаляться. Если же столбец option_name таблицы БД не содержит названия, но соседний столбец option_value содержит значение, то такая строка будет показана для того, чтобы вы смогли найти и устранить проблемы. Если же вы желаете удалить соответствующие строки - сделайте это другим способом. Пожалуйста, проверьте внимательно данную информацию и удаляйте только те опции, которые являются осиротелыми или ненужными. Возможно осиротелые опции Возникли вопросы? Для поддержки есть форум %s Опции RSS Удалено %d "rss_hash" опций Удаленные опции Возвратится к %s Искать Поищите файлы по: Выделить все Некоторая информация доступна на странице %s, проверьте ее. Отправить Опции "rss_" убраны из WordPress версии 2.8. В таблице все они выделяются и их можно безопасно удалить. Таблица опций содержит %s найденных строк. Следующие опции, похоже, являются осиротелыми. Опции, которые невозможно выделить - известные, и созданы во время обновления, или являются резервными копиями, или теми опциями, которые не заданы функциями get_option или get_settings в файлах папки с плагинами. Если вы все же желаете их удалить, делайте это на свой страх и риск другими способами. Эта секция содержит "RSS" опции, которые были добавлены в таблицу wp_options из главной страницы админпанели (также эта страница называется "приборной панелью", или "дашбордом") блога, или другими файлами, которые работают с RSS-потоками и кэшируют их. <br />В каждой паре верхняя опция - кэш потока, нижняя - временная метка.<br />Показанные здесь записи могут включать опции, которые являются <strong>активными в текущий момент</strong>.<br />Когда это отображает данный плагин, то пары "rss_" опций с датами новее, или такими же, как дата новейшей 14-й пары опций (те, которые вероятно всего будут текущими) не имеют чекбокса, но начинаются с символа "-", и заканчиваются "<em># %1$s</em>", написанные курсивом.<br />Старшие "rss_" опции можно выбирать. Они заканчиваются "<strong># %2$s</strong>", жирным шрифтом. Следующие опции не были разбиты на пары корректно. Внимательно проверьте информацию о них перед тем, как удалять. Чем меньшее количество "rss_hash" пар опций вы нашли, тем меньшая вероятность того, что вы будете испытывать проблемы с памятью в течении "Найти". Однако, в зависимости от количества лент новостей, которые являются текущими, поиск может не включать любые старые записи, которые могут быть удалены.<br />Чем больше "rss_hash" пар вы найдете, тем большая вероятность того, что старые пары, которые могут быть удалены, будут включены в список удаляемых. Но есть вероятность возникновения проблем памяти во время "Найти".<br />Желательно начать поиск с более меньшим количеством пар, и увеличивать его постепенно, если вы хотите, в дальнейшем. Если вы получаете ошибки памяти, используйте меньшее количество.<br />Рекомендуется проверить результаты сканирования до удаления чего-либо, чтобы убедиться, что вы действительно хотите удалить это. Плагин не смог открыть папку/файл. Часто это случается из-за несоответствия настроек прав доступа к файлам и папкам. Например, настройка, разрешающая только чтение. На папки может быть наложен запрет на чтение для всех пользователей, но скрипты должны быть обязательно доступны для чтения. Опции, которые находятся в файлах, которые не могут быть прочитаны, могут появится в списке осиротелых, когда на самом деле они таковыми не являются. Во многих случаях, знания о папках/файлах могут помочь в идентификации опций, которые действительно не являются осиротелыми. Плагин опрашивает таблицу wp_options, и ожидает ответ-массив содержащий хотя бы 1 строку. Эта ошибка может быть результатом различных неточностей. Например, в таблице wp_option нет строк с параметром autoload=yes (этого практически не может быть), или же на самом деле нет "rss_hash" строк, или это ошибка данных (например, обрыв соединения, утечка памяти). Если вы получаете это сообщение об ошибке, обратите внимание на сообщения WPDB для получения более детальной информации. Ошибка с autoload=yes запросом (ядро/плагин/опции темы оформления), или autoload!=yes запроса (rss_hash опции) означает, что ни одна из соответствующих строк не будет доступна для просмотра или удаления из базы данных до тех пор, пока проблема не будет решена. Плагин сравнивает существующие значения таблицы wp_options с теми, которые были получены в результате сканирования файлов PHP на наличие функции get_option. Некоторые файлы, однако, используют альтернативный синтаксис вроде get_option(переменная &#36;), или get_option(переменная 'prefix_' . &#36;). Эти имена опций не будут совпадать с существующими в таблице, и могут присутствовать в списке опций-сирот, когда на самом деле они таковыми не являются. Во многих случаях, если известны файлы и префиксы, используемые ними, то это должно облегчить распознавание опций, являющихся действительно осиротелыми.<br />*Обратите внимание, если вы получите это предупреждение с файлом плагина (желательно, чтобы плагин был от <u><i>WordPress.com Plugin Directory</i></u>) и вы знаете, что это не это не потенциальная проблема (например, некоторые файлы ядра WordPress, статистический плагин WordPress.com, и этот плагин содержит в себе альтернативный синтаксис, НО <u><i>в списке нет опций, ассоциированных с ними</i></u>), пожалуйста, посетите наш блог и оставьте комментарий вроде "какой-то плагин содержит альтернативный синтаксис, но все ОК", и я, возможно, смогу добавить его в "список игнорируемых" в будущих релизах. Большое спасибо. %s Существует опция без автоматической загрузки, без имени, со значением: %s Существует опция с автоматической загрузкой (autoload yes), без имени, со значением: %s Не найдено "rss_" опций Эти сообщения возвращаются движком WordPress. Чтобы избежать чрезмерного потребления памяти при "%s", не допускайте отображения всех "rss_hash" опций, иначе вы не будете иметь возможности просмотреть всю информацию. Внимательно проверьте предполагаемые осиротелые опции прежде чем сделать свой выбор: Искать название_опции(ий): Инструменты Спасибо за перевод Попробуйте найти в поисковике Google название_опции. Просмотреть информацию о выбранных опциях РАЗЫСКИВАЮТСЯ - Сообщения об ошибках РАЗЫСКИВАЮТСЯ - Новые переводы ПРЕДУПРЕЖДЕНИЕ О предупреждениях Ошибка базы данных WordPress Да, удалить ВСЕ эти опции из таблицы wp_options. все_или_часть_названия_опции проверить возраст соответствующей "_ts" опции. дней назад одной из указанных строк, например, первому экрану имеет строку с опцией известная опция ядра WordPress трем строкам, разделенным %s, например, префикс %s другие_слова префикс%1$sслово%2$sдругое_слово запрос не возвратил массив необходима регистрация для постинга Найдено в: 