<script context="module" lang="ts">
    export { default as layout } from '@/components/layouts/Auth.svelte';
</script>

<script lang="ts">
    import { useForm } from '@inertiajs/svelte';

    const form = useForm({
        email: '',
        password: '',
        remember: false,
    })
</script>

<div class="card bg-base-200 w-full max-w-[500px] shadow">
    <form on:submit|preventDefault={$form.post('/login')} class="card-body flex felx-col items-center">
        <div class="form-control w-full">
            <label class="label" for="email">
                <span class="label-text">Email</span>
            </label>
            <input bind:value={$form.email} type="email" name="email" placeholder="Email" class="input input-bordered {$form.errors.email ? 'input-error' : ''}" required />
            {#if $form.errors.email}
                <span class="label-text-alt text-error text-left mt-1">{$form.errors.email}</span>
            {/if}
        </div>
        <div class="form-control w-full">
            <label class="label" for="password">
                <span class="label-text">Password</span>
            </label>
            <input bind:value={$form.password} type="password" name="password" placeholder="Password" class="input input-bordered {$form.errors.password ? 'input-error' : ''}" required />
            {#if $form.errors.password}
                <span class="label-text-alt text-error text-left mt-1">{$form.errors.password}</span>
            {/if}
        </div>
        <div class="form-control w-full">
            <label class="label cursor-pointer justify-start gap-2" for="remember">
                <input bind:checked={$form.remember} type="checkbox" class="checkbox {$form.errors.remember ? 'checkbox-error' : ''}" name="remember" />
                <span class="label-text">Remember me</span>
            </label>
            {#if $form.errors.remember}
                <span class="label-text-alt text-error text-left mt-1">{$form.errors.remember}</span>
            {/if}
        </div>

        <div class="form-control w-full">
            <button class="btn btn-primary" type="submit">Login</button>
        </div>
    </form>
</div>
