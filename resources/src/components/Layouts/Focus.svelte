<main class="min-h-screen lg:p-16 md:p-8 p-4 flex flex-col">
    <div class="background-particles bg-base-200 h-full w-full flex-1 rounded-xl relative lg:p-14 md:p-10 p-4 flex items-center justify-center">
        <div class="absolute inset-0 lg:-inset-10 md:-inset-6 background-people"/>
        <slot />
    </div>
</main>

<style lang="scss">
    .background-particles {
        background-image:
            url(@/assets/img/quickstart-particle1.svg),
            url(@/assets/img/quickstart-particle2.svg);
        background-repeat: no-repeat, no-repeat;
        background-position: top 10px right 10px, bottom 10px left 10px;

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
            background-image:
                url(@/assets/img/quickstart-male.png),
                url(@/assets/img/quickstart-female.png);
        }
        background-repeat: no-repeat, no-repeat;
        background-position: top left, bottom right;

        background-size: 9rem, 6rem;

        @media (min-width: 768px) {
            background-size: 12rem, 8rem;
        }

        @media (min-width: 1024px) {
            background-size: 15rem, 10rem;
        }
    }
</style>
