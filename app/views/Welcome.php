<section class="body">
    <div class="content-wrapper">
        <h3>Elemental</h3>

        <p class="desc">
            An MVC PHP framework for dynamic, user-friendly coding experiences.
        </p>

        <div class="content">
            <div class="section bordered">
                <div class="section-header">
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 512 512">
                        <path fill="currentColor" d="M16 352a48.05 48.05 0 0 0 48 48h133.88l-4 32H144a16 16 0 0 0 0 32h224a16 16 0 0 0 0-32h-49.88l-4-32H448a48.05 48.05 0 0 0 48-48v-48H16Zm240-16a16 16 0 1 1-16 16a16 16 0 0 1 16-16M496 96a48.05 48.05 0 0 0-48-48H64a48.05 48.05 0 0 0-48 48v192h480Z" />
                    </svg>
                    <span>Explore the <a href="https://inkwell.anees.dev">Demo</a></span>
                </div>
                <p class="text">Dive into the world of <a href="https://inkwell.anees.dev">Inkwell</a>, a unique space dedicated to the pure essence of storytelling. It's a showcase project developed entirely with Elemental.</p>
                <p class="text">Discover the source code on <a href="https://github.com/AneesMuzzafer/Inkwell">GitHub</a>.</p>
            </div>
            <div class="section">
                <div class="section-header">
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 512 512">
                        <path fill="currentColor" d="M202.24 74C166.11 56.75 115.61 48.3 48 48a31.36 31.36 0 0 0-17.92 5.33A32 32 0 0 0 16 79.9V366c0 19.34 13.76 33.93 32 33.93c71.07 0 142.36 6.64 185.06 47a4.11 4.11 0 0 0 6.94-3V106.82a15.89 15.89 0 0 0-5.46-12A143 143 0 0 0 202.24 74m279.68-20.7A31.33 31.33 0 0 0 464 48c-67.61.3-118.11 8.71-154.24 26a143.31 143.31 0 0 0-32.31 20.78a15.93 15.93 0 0 0-5.45 12v337.13a3.93 3.93 0 0 0 6.68 2.81c25.67-25.5 70.72-46.82 185.36-46.81a32 32 0 0 0 32-32v-288a32 32 0 0 0-14.12-26.61" />
                    </svg>
                    <span>
                        <a href="https://github.com/AneesMuzzafer/elemental">Documentation</a>
                    </span>
                </div>
                <p class="text">Unlock the full potential of Elemental with our comprehensive <a href="https://github.com/AneesMuzzafer/elemental">documentation</a>, covering every aspect of the framework. Get started on your journey towards efficient and elegant web development.</p>
            </div>
        </div>
    </div>

    <style>
        .body {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #eef2ff;
            padding: 20px;
        }

        .content-wrapper {
            width: 50%;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 10px 15px -3px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        h3 {
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
            color: #4338ca;
            font-size: 3rem;
            margin-bottom: 1.5rem;
        }

        a {
            color: #9AA32C;
        }

        .desc {
            color: #666;
            font-size: 18px;
        }

        .content {
            display: flex;
            flex-wrap: wrap;
            height: 100%;
            padding: 30px 0px;
        }

        .section {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 20px;
            font-size: 14px;
            color: #888;
            line-height: 20px;
            min-width: 250px;
        }

        .bordered {
            padding-right: 30px;
            margin-right: 30px;
            border-right: solid 1px #999;
        }

        .section-header {
            display: flex;
            justify-content: start;
            align-items: center;
            font-size: 1rem;
            color: #111;
            gap: 10px;
            margin-top: 20px;
        }

        .section-header a {
            color: #111;
        }

        .section-header a:hover {
            color: #4338ca;
        }

        .text {
            margin: 0 30px;
        }

        .icon {
            color: #4338ca;
        }

        @media (max-width: 1000px) {
            .content-wrapper {
                width: 100%;
            }
        }

        @media (max-width: 600px) {
            .body {
                height: auto;
            }

            .bordered {
                padding-right: 0px;
                margin-right: 0px;
                border-right: 0;
            }
        }
    </style>
</section>
