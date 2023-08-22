<script lang="ts">
    import img from '@/assets/img/contact-form-img.jpg'
    import Splash from '@/components/SVG/splash-contact-form.svg.svelte'
</script>

<section class="max-md:mb-15 container mx-auto mb-20 px-[15px] max-sm:mb-10">
    <div class="mb-[-30px] ml-[-30px] flex flex-wrap items-center">
        <div class="c-col relative mb-[30px] ml-[30px] flex-col max-md:w-full">
            <Splash />

            <div
                class="absolute left-1/2 top-1/2 w-[75.92%] -translate-x-1/2 -translate-y-1/2 overflow-hidden rounded-[20px]"
            >
                <img src={img} alt={img} class="h-auto w-full" />
            </div>
        </div>

        <form
            action="#"
            class="c-col mb-[30px] ml-[30px] flex flex-col max-md:w-full"
        >
            <input type="text" name="contact_name" placeholder="Full Name" />

            <input
                type="email"
                name="contact_email"
                placeholder="Email Address"
            />

            <input
                type="number"
                name="contact_phone"
                placeholder="Phone Number"
            />

            <textarea name="contact_message" placeholder="Message"></textarea>

            <button
                type="submit"
                class="contact-btn max-w-[176px] rounded-[40px] bg-primary px-6 py-4 font-medium text-white"
            >
                Send Message
            </button>
        </form>
    </div>
</section>

<style lang="scss">
    input,
    textarea {
        margin-bottom: 24px;
        padding: 16px 18px;
        background-color: transparent;
        border: 1px solid #999;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 500;
        line-height: 1.66;
    }

    textarea {
        resize: none;
        height: 168px;
        overflow-y: auto;
    }

    input::placeholder,
    textarea::placeholder {
        color: #999;
    }

    .contact-btn {
        transition: background 0.3s ease-out;
    }

    .contact-btn:hover {
        background-color: #135291;
    }

    .c-col {
        width: calc(50% - 30px);
    }

    @media screen and (max-width: 1024px) {
        input,
        textarea {
            padding: 14px 16px;
            font-size: 14px;
        }
    }

    @media screen and (max-width: 640px) {
        input,
        textarea {
            padding: 12px 14px;
            font-size: 12px;
        }

        .c-col {
            width: 100%;
        }
    }
</style>
