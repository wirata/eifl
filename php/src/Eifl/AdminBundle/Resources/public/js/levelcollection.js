var $collectionHolder;
var $levelcollectionHolder1;
var $levelcollectionHolder2;
var $classcollectionHolder;
var $lessoncollectionHolder;

// setup an "add new level" link
var $addLevelLink1 = $('<a href="#" class="add_levels_link">Add new Level</a>');
var $newLevelLink1Li = $('<li></li>').append($addLevelLink1);
// setup an "add new level" link
var $addLevelLink2 = $('<a href="#" class="add_levels_link">Add new Level</a>');
var $newLevelLink2Li = $('<li></li>').append($addLevelLink2);
// setup an "add new class" link
var $addClassLink = $('<a href="#" class="add_class_link">Add new Class</a>');
var $newClassLinkLi = $('<li></li>').append($addClassLink);
// setup an "add new lesson" link
var $addLessonLink = $('<a href="#" class="add_lesson_link">Add new Lesson</a>');
var $newLessonLinkLi = $('<li></li>').append($addLessonLink);

function addLessonForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);

    // add a delete link to the new form
    addLevelFormDeleteLink($newFormLi);
}
function addLevelForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);

    // add a delete link to the new form
    addLevelFormDeleteLink($newFormLi);
}

function addClassForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);

    // add a delete link to the new form
    addClassFormDeleteLink($newFormLi);
}

function addLevelFormDeleteLink($levelFormLi) {
    var $removeFormA = $('<a href="#">delete this level</a>');
    $levelFormLi.append($removeFormA);

    $removeFormA.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // remove the li for the tag form
        $levelFormLi.remove();
    });
}
function addLessonFormDeleteLink($levelFormLi) {
    var $removeFormA = $('<a href="#">delete this lesson</a>');
    $levelFormLi.append($removeFormA);

    $removeFormA.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // remove the li for the tag form
        $levelFormLi.remove();
    });
}

function addClassFormDeleteLink($levelFormLi) {
    var $removeFormA = $('<a href="#">delete this Class</a>');
    $levelFormLi.append($removeFormA);

    $removeFormA.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // remove the li for the tag form
        $levelFormLi.remove();
    });
}

jQuery(document).ready(function() {
    // Get the ul that holds the collection of levels
    $levelcollectionHolder1 = $('ul.level');

    // add the "add a levels" anchor and li to the levels ul
    $levelcollectionHolder1.append($newLevelLink1Li);

    // add a delete link to all of the existing tag form li elements
    $levelcollectionHolder1.find('.level_row').each(function() {
        addLevelFormDeleteLink($(this));
    });

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $levelcollectionHolder1.data('index', $levelcollectionHolder1.find(':input').length);

    $addLevelLink1.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form (see next code block)
        addLevelForm($levelcollectionHolder1, $newLevelLink1Li);
    });
    /////////////////////////////////////////////////
    // Get the ul that holds the collection of levels
    $lessoncollectionHolder = $('ul.lesson');

    // add the "add a levels" anchor and li to the levels ul
    $lessoncollectionHolder.append($newLessonLinkLi);

    // add a delete link to all of the existing tag form li elements
    $lessoncollectionHolder.find('.level_row').each(function() {
        addLessonFormDeleteLink($(this));
    });

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $lessoncollectionHolder.data('index', $lessoncollectionHolder.find(':input').length);

    $addLessonLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form (see next code block)
        addLessonForm($lessoncollectionHolder, $newLessonLinkLi);
    });
    /////////////////////////////////////////////////
    // Get the ul that holds the collection of levels
    $levelcollectionHolder2 = $('ul.newlevel');

    // add the "add a levels" anchor and li to the levels ul
    $levelcollectionHolder2.append($newLevelLink2Li);

    // add a delete link to all of the existing tag form li elements
    $levelcollectionHolder2.find('.level_row').each(function() {
        addLevelFormDeleteLink($(this));
    });

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $levelcollectionHolder2.data('index', $levelcollectionHolder2.find(':input').length);

    $addLevelLink2.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form (see next code block)
        addLevelForm($levelcollectionHolder2, $newLevelLink2Li);
    });
    /////////////////////////////////////////////////
    // Get the ul that holds the collection of levels
    $classcollectionHolder = $('ul.class');

    // add the "add a levels" anchor and li to the levels ul
    $classcollectionHolder.append($newClassLinkLi);

    // add a delete link to all of the existing tag form li elements
    $classcollectionHolder.find('.level_row').each(function() {
        addClassFormDeleteLink($(this));
    });

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $classcollectionHolder.data('index', $classcollectionHolder.find(':input').length);

    $addClassLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form (see next code block)
        addLevelForm($classcollectionHolder, $newClassLinkLi);
    });
});