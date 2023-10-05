<main class="flex min-h-screen flex-col p-4 md:p-8 lg:p-16">
    <div class="background-particles relative flex flex-1 rounded-xl bg-base-200 p-4 md:p-10 lg:p-14">
        <div class="background-people absolute inset-0 md:-inset-6 lg:-inset-10" />
        <slot />
    </div>
</main>

<style lang="scss">
    .background-particles {
        background-image: url(@/assets/img/quickstart-particle1.svg), url(@/assets/img/quickstart-particle2.svg);
        background-repeat: no-repeat, no-repeat;
        background-position:
            top 10px right 10px,
            bottom 10px left 10px;

        background-size: 9rem, 6rem;

        @media (min-width: 768px) {
            background-size: 12rem, 8rem;
        }

        @media (min-width: 1024px) {
            background-size: 15rem, 10rem;
        }
    }

    .background-people {
        @media (min-width: 768px) {
            background-image: url(@/assets/img/quickstart-male.png), url(@/assets/img/quickstart-female.png);
        }
        background-repeat: no-repeat, no-repeat;
        background-position:
            top left,
            bottom right;

        background-size: 9rem, 6rem;

        @media (min-width: 768px) {
            background-size: 12rem, 8rem;
        }

        @media (min-width: 1024px) {
            background-size: 15rem, 10rem;
        }
    }
</style>
