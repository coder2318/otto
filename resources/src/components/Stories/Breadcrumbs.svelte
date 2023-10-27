<script lang="ts">
    import { inertia } from '@inertiajs/svelte'
    import { faBook, faCreditCard, faList } from '@fortawesome/free-solid-svg-icons'
    import Fa from 'svelte-fa'
    import photo from '@/assets/img/story-edit/photo.svg'
    import table from '@/assets/img/story-edit/table.svg'
    import order from '@/assets/img/story-edit/order.svg'

    export let step: number = 0
    export let story: { data: App.Story }
</script>

<div class="otto-breadcrumb">
    <div class="otto-container">
        <div class="wrap">
            <a class="step-breadcrumb" class:inactive={step < 1} href="/stories/{story.data.id}/edit" use:inertia>
                <div class="step-icon">
                    <img src={photo} alt="Icon" />
                </div>
                <p>Table of contents</p>
                <span class="step-line"></span>
            </a>

            <a class="step-breadcrumb" class:inactive={step < 2} href="/stories/{story.data.id}/cover" use:inertia>
                <div class="step-icon">
                    <img src={table} alt="Icon" />
                </div>
                <p>Make a book cover</p>
                <span class="step-line"></span>
            </a>

            <a class="step-breadcrumb" class:inactive={step < 3} href="/stories/{story.data.id}/order" use:inertia>
                <div class="step-icon">
                    <img src={order} alt="Icon" />
                </div>
                <p>Order your book</p>
                <span class="step-line"></span>
            </a>
        </div>
    </div>
</div>

<style lang="scss">
    .otto-breadcrumb {
        padding: 50px 0;
        .wrap {
            display: flex;
            justify-content: space-between;
        }

        .step-breadcrumb {
            display: flex;
            align-items: center;
            width: 100%;
            margin-right: 24px;
            padding: 0;
            padding-bottom: 20px;
            border: none;
            position: relative;

            &:last-child {
                margin-right: 0;
            }

            .step-line {
                height: 8px;
                width: 100%;
                position: absolute;
                background-color: #0c345c;
                border-radius: 40px;
                left: 0;
                bottom: 0;
                transition: 0.3s;
            }

            p {
                font-size: 20px;
                color: #0c345c;
                line-height: 1.1;
                transition: 0.3s;
            }

            .step-icon {
                padding: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                width: 50px;
                height: 50px;
                margin-right: 24px;
                background-color: #ffd885;
                transition: 0.3s;

                img {
                    display: block;
                    width: 30px;
                    opacity: 1;
                }
            }

            &.inactive {
                .step-icon {
                    background-color: #fff;

                    img {
                        opacity: 0.1;
                    }
                }
                .step-line {
                    background-color: #e2dacf;
                }
                p {
                    color: #666;
                }
            }

            &:hover {
                .step-line {
                    background-color: #0c345c;
                }
            }
        }
    }

    @media (max-width: 767px) {
        .otto-breadcrumb {
            padding-bottom: 30px;

            .step-breadcrumb {
                flex-direction: column;
                padding-bottom: 16px;
                margin-right: 16px;

                .step-icon {
                    margin-right: 0;
                    margin-bottom: 7px;
                }

                p {
                    font-size: 16px;
                    text-align: center;
                    max-width: 100px;
                }

                .step-line {
                    height: 6px;
                }
            }
        }
    }
</style>
