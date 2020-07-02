<div class="content mx-auto my-12 w-4/5 sm:w-3/5">

    {{ this.getContent() }}

    <div class="row">
        <ul>
            {% for post in page.convertedItems %}
            <li class="block border-2 border-{{ theme }}-gray py-8 px-24 h-64 rounded-full mb-10 shadow-md">
                <h2 class="text-4xl truncate"><a href="/post/read/{{ post.id }}">{{ post.title }}</a></h2>
                <p class="h-24 truncate-3-lines overflow-y-hidden mb-2">{{ post.convertedBody }}</p>
                <p class="text-right">{{ post.created_at }}</p>
            </li>
            {% endfor %}
        </ul>
    </div>

    <div class="row">
        <div class="col-sm-1">

        </div>
        <div class="col-sm-11">
            <nav>
                <ul class="pagination flex pl-0 rounded list-none flex-wrap mb-10">
                    <li class="flex-1 lg:hidden xl:block"><?php echo $this->tag->linkTo(["post", "First", 'class' => 'block mx-auto text-xs font-semibold flex w-8 h-8 mx-1 p-0 rounded-full items-center justify-center leading-tight relative border border-solid border-green-500 bg-white text-green-500', 'id' => 'first']) ?></li>
                    <li class="flex-1"><?php echo $this->tag->linkTo(["post?page=" . $page->getPrevious(), "Prev", 'class' => 'block mx-auto text-xs font-semibold flex w-8 h-8 mx-1 p-0 rounded-full items-center justify-center leading-tight relative border border-solid border-green-500 bg-white text-green-500', 'id' => 'prev']) ?></li>

                    {% if page.getCurrent() % 10 is 0  %}
                        {%- set minus = 10 -%}
                    {% else %}
                        {%- set minus = page.getCurrent() % 10 -%}
                    {% endif %}

                    {%- set min = page.getCurrent() - (minus) + 1 -%}
                    {%- set max = page.getCurrent() - (minus) + 10 -%}

                    {% if max >= page.getLast()  %}
                        {%- set max = page.getLast() -%}
                    {% endif %}

                    {% for pg in ( min )..( max ) %}
                        <li class="flex-1 hidden lg:block">
                            {% if pg === page.getCurrent() %}
                                <a href="/post?page={{ pg }}" class="text-xs flex w-8 h-8 mx-1 p-0 rounded-full items-center justify-center relative border border-solid border-{{ theme }}-primary bg-white bg-{{ theme }}-primary text-white">{{ pg }}</a>
                            {% else %}
                                <a href="/post?page={{ pg }}" class="text-xs flex w-8 h-8 mx-1 p-0 rounded-full items-center justify-center relative border border-solid border-{{ theme }}-primary bg-white text-{{ theme }}-primary">{{ pg }}</a>
                            {% endif %}
                        </li>
                    {% endfor %}

                    <li class="flex-1"><?php echo $this->tag->linkTo(["post?page=" . $page->getNext(), "Next", 'class' => 'block mx-auto first:ml-0 text-xs font-semibold flex w-8 h-8 mx-1 p-0 rounded-full items-center justify-center leading-tight relative border border-solid border-green-500 bg-white text-green-500', 'id' => 'next']) ?></li>
                    <li class="flex-1 lg:hidden xl:block"><?php echo $this->tag->linkTo(["post?page=" . $page->getLast(), "Last", 'class' => 'block mx-auto first:ml-0 text-xs font-semibold flex w-8 h-8 mx-1 p-0 rounded-full items-center justify-center leading-tight relative border border-solid border-green-500 bg-white text-green-500', 'id' => 'last']) ?></li>
                </ul>
            </nav>
        </div>
    </div>

    <p class="flex items-center">
        <span class="flex-1">
            {%- if session.role -%}
            <a href="{{ url.get(['for':'post-create']) }}"
               class="bg-{{ theme }}-primary hover:bg-{{ theme }}-accent text-white font-bold p-3 rounded box-content">
                Create
            </a>
            {%- endif -%}
        </span>
        <span class="bg-{{ theme }}-secondary rounded-full p-3 mr-2 text-white">Current Page {{ page.getCurrent() }}</span>
        <span class="bg-{{ theme }}-secondary rounded-full p-3 text-white">Total Articles {{ page.getTotalItems() }}</span>
    </p>
</div>