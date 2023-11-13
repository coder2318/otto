<script lang="ts">
    import blueMicrophone from '@/assets/img/blue-microphone.svg'
    export let tip
    export let title
</script>

<section class="bannerTip">
    <div class="otto-container">
        <div class="block">
            <div class="wrap">
                <img class="bannerTip-icon" src={blueMicrophone} alt="Microphone" />
                <span class="bannerTip-subtitle">{title}</span>
            </div>
            <p class="fz_h4">{tip}</p>
        </div>
    </div>
</section>

<style lang="scss">
    .bannerTip {
        position: relative;
        margin-bottom: 20px;
        color: #0c345c;

        .block {
            background-color: #cfe3f3;
            padding: 16px 30px;
            border-radius: 24px;

            @media (max-width: 767px) {
                padding: 16px 20px;
            }
        }

        .wrap {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }

        &-icon {
            margin-right: 10px;
        }

        &-subtitle {
            font-weight: 700;
            font-size: 20px;
            color: inherit;
        }
        p {
            color: inherit;
        }
    }
</style>
