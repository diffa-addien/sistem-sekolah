<?php $pager->setSurroundCount(2) ?>

<nav aria-label="Page navigation">
    <ul class="inline-flex items-center -space-x-px">
        <?php if ($pager->hasPrevious()) : ?>
            <li>
                <a href="<?= $pager->getFirst() ?>" class="px-3 py-2 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700" aria-label="<?= lang('Pager.first') ?>">
                    <span aria-hidden="true">First</span>
                </a>
            </li>
        <?php endif ?>

        <?php if ($pager->hasPrevious()) : ?>
            <li>
                <a href="<?= $pager->getPrevious() ?>" class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700" aria-label="<?= lang('Pager.previous') ?>">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            </li>
        <?php endif ?>

        <?php foreach ($pager->links() as $link) : ?>
            <li>
                <a href="<?= $link['uri'] ?>" class="px-3 py-2 leading-tight <?= $link['active'] ? 'text-purple-600 bg-purple-50 border-purple-300' : 'text-gray-500 bg-white border-gray-300' ?> hover:bg-gray-100 hover:text-gray-700">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach ?>

        <?php if ($pager->hasNext()) : ?>
            <li>
                <a href="<?= $pager->getNext() ?>" class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700" aria-label="<?= lang('Pager.next') ?>">
                     <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            </li>
        <?php endif ?>

        <?php if ($pager->hasNext()) : ?>
             <li>
                <a href="<?= $pager->getLast() ?>" class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700" aria-label="<?= lang('Pager.last') ?>">
                    <span aria-hidden="true">Last</span>
                </a>
            </li>
        <?php endif ?>
    </ul>
</nav>