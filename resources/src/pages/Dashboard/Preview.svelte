<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import Lorem from '@/assets/md/lorem.md'
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Preview</title>
</svelte:head>

<div class="bg-transparent p-4">
    <div
        class="not-prose rounded-box grid gap-3 border border-base-content/20 bg-base-200 p-8 text-base-content"
    >
        <!-- button -->
        <div class="grid grid-cols-3 gap-2">
            <button class="btn">Default</button>
            <button class="btn btn-neutral">Neutral</button>
            <button class="btn btn-primary">Primary</button>
            <button class="btn btn-secondary">Secondary</button>
            <button class="btn btn-accent">Accent</button>
            <button class="btn btn-info">Info</button>
            <button class="btn btn-success">Success</button>
            <button class="btn btn-warning">Warning</button>
            <button class="btn btn-error">Error</button>
        </div>
        <!-- badge -->
        <div class="grid grid-cols-3 place-items-center gap-2">
            <span class="badge">Default</span>
            <span class="badge badge-neutral">Neutral</span>
            <span class="badge badge-primary">Primary</span>
            <span class="badge badge-secondary">Secondary</span>
            <span class="badge badge-accent">Accent</span>
            <span class="badge badge-info">Info</span>
            <span class="badge badge-success">Success</span>
            <span class="badge badge-warning">Warning</span>
            <span class="badge badge-error">Error</span>
        </div>
        <div class="flex flex-col gap-3">
            <div class="flex flex-col gap-3 md:flex-row">
                <div class="md:w-1/2">
                    <!-- tabs -->
                    <div class="tabs">
                        <button class="tab tab-lifted">Tab</button>
                        <button class="tab tab-active tab-lifted">Tab</button>
                        <button class="tab tab-lifted">Tab</button>
                    </div>
                    <!-- link -->
                    <div class="flex flex-col">
                        <span class="link">I'm a simple link</span>
                        <span class="link-primary link">I'm a simple link</span>
                        <span class="link-secondary link"
                            >I'm a simple link</span
                        >
                        <span class="link-accent link">I'm a simple link</span>
                    </div>
                </div>
                <div class="flex flex-col gap-3 md:w-1/2">
                    <progress value="20" max="100" class="progress"
                        >Default</progress
                    >
                    <progress
                        value="25"
                        max="100"
                        class="progress progress-primary"
                    >
                        Primary
                    </progress>
                    <progress
                        value="30"
                        max="100"
                        class="progress progress-secondary"
                    >
                        Secondary
                    </progress>
                    <progress
                        value="40"
                        max="100"
                        class="progress progress-accent"
                    >
                        Accent
                    </progress>
                    <progress
                        value="45"
                        max="100"
                        class="progress progress-info"
                    >
                        Info
                    </progress>
                    <progress
                        value="55"
                        max="100"
                        class="progress progress-success"
                    >
                        Success
                    </progress>
                    <progress
                        value="70"
                        max="100"
                        class="progress progress-warning"
                    >
                        Warning
                    </progress>
                    <progress
                        value="90"
                        max="100"
                        class="progress progress-error"
                    >
                        Error
                    </progress>
                </div>
            </div>

            <div class="flex flex-col gap-3 md:flex-row">
                <!-- stat -->
                <div class="stats border border-base-300 bg-base-300 md:w-1/2">
                    <div class="stat">
                        <div class="stat-title">Total Page Views</div>
                        <div class="stat-value">89,400</div>
                        <div class="stat-desc">21% more than last month</div>
                    </div>
                </div>

                <!-- radial progress -->
                <div
                    class="flex flex-wrap items-center justify-center gap-3 md:w-1/2"
                >
                    <div
                        class="radial-progress"
                        style="--value: 60; --size: 3.5rem"
                    >
                        60%
                    </div>
                    <div
                        class="radial-progress"
                        style="--value: 75; --size: 3.5rem"
                    >
                        75%
                    </div>
                    <div
                        class="radial-progress"
                        style="--value: 90; --size: 3.5rem"
                    >
                        90%
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-3">
            <div class="flex flex-col gap-3 md:flex-row">
                <div class="md:w-1/2">
                    <!-- toggle -->
                    <div>
                        <input type="checkbox" class="toggle mr-1" checked />
                        <input
                            type="checkbox"
                            class="toggle toggle-primary mr-1"
                            checked
                        />
                        <input
                            type="checkbox"
                            class="toggle toggle-secondary mr-1"
                            checked
                        />
                        <input
                            type="checkbox"
                            class="toggle toggle-accent mr-1"
                            checked
                        />
                    </div>
                    <!-- checkbox -->
                    <div>
                        <input type="checkbox" class="checkbox mr-1" checked />
                        <input
                            type="checkbox"
                            class="checkbox-primary checkbox mr-1"
                            checked
                        />
                        <input
                            type="checkbox"
                            class="checkbox-secondary checkbox mr-1"
                            checked
                        />
                        <input
                            type="checkbox"
                            class="checkbox-accent checkbox mr-1"
                            checked
                        />
                    </div>
                    <!-- radio -->
                    <div>
                        <input
                            type="radio"
                            name="radio-1"
                            class="radio mr-1"
                            checked
                        />
                        <input
                            type="radio"
                            name="radio-1"
                            class="radio-primary radio mr-1"
                        />
                        <input
                            type="radio"
                            name="radio-1"
                            class="radio-secondary radio mr-1"
                        />
                        <input
                            type="radio"
                            name="radio-1"
                            class="radio-accent radio mr-1"
                        />
                    </div>
                </div>
                <!-- range -->
                <div class="md:w-1/2">
                    <input
                        type="range"
                        min="0"
                        max="100"
                        value="90"
                        class="range range-xs"
                    />
                    <input
                        type="range"
                        min="0"
                        max="100"
                        value="70"
                        class="range range-primary range-xs"
                    />
                    <input
                        type="range"
                        min="0"
                        max="100"
                        value="50"
                        class="range range-secondary range-xs"
                    />
                    <input
                        type="range"
                        min="0"
                        max="100"
                        value="40"
                        class="range range-accent range-xs"
                    />
                </div>
            </div>
            <!-- input -->
            <div class="flex flex-col gap-3 md:flex-row">
                <div class="flex flex-col gap-3 md:w-1/2">
                    <input
                        type="text"
                        placeholder="Default"
                        class="input input-bordered w-full"
                    />
                    <input
                        type="text"
                        placeholder="Primary"
                        class="input input-bordered input-primary w-full"
                    />
                    <input
                        type="text"
                        placeholder="Secondary"
                        class="input input-bordered input-secondary w-full"
                    />
                    <input
                        type="text"
                        placeholder="Accent"
                        class="input input-bordered input-accent w-full"
                    />
                </div>
                <div class="flex flex-col gap-3 md:w-1/2">
                    <input
                        type="text"
                        placeholder="Info"
                        class="input input-bordered input-info w-full"
                    />
                    <input
                        type="text"
                        placeholder="Success"
                        class="input input-bordered input-success w-full"
                    />
                    <input
                        type="text"
                        placeholder="Warning"
                        class="input input-bordered input-warning w-full"
                    />
                    <input
                        type="text"
                        placeholder="Error"
                        class="input input-bordered input-error w-full"
                    />
                </div>
            </div>
            <!-- navbar -->
            <div class="navbar rounded-box bg-primary text-primary-content">
                <div class="flex-none">
                    <button class="btn btn-square btn-ghost">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            class="inline-block h-5 w-5 stroke-current"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                        </svg>
                    </button>
                </div>
                <div class="flex-1">
                    <button class="btn btn-ghost text-xl normal-case"
                        >Logo</button
                    >
                </div>
            </div>
            <!-- breadcrumbs -->
            <div class="breadcrumbs text-sm">
                <ul>
                    <li>
                        <a href="#hello">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                class="mr-2 h-4 w-4 stroke-current"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"
                                />
                            </svg>
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="#hello">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                class="mr-2 h-4 w-4 stroke-current"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"
                                />
                            </svg>
                            Documents
                        </a>
                    </li>
                    <li>
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            class="mr-2 h-4 w-4 stroke-current"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                            />
                        </svg>
                        Add Document
                    </li>
                </ul>
            </div>
            <div class="flex gap-3">
                <!-- headings -->
                <div class="flex flex-grow flex-col gap-3">
                    <div class="text-4xl font-bold">Text Size 1</div>
                    <div class="text-3xl font-bold">Text Size 2</div>
                    <div class="text-2xl font-bold">Text Size 3</div>
                    <div class="text-xl font-bold">Text Size 4</div>
                    <div class="text-lg font-bold">Text Size 5</div>
                    <div class="text-sm font-bold">Text Size 6</div>
                    <div class="text-xs font-bold">Text Size 7</div>
                </div>
                <!-- step -->
                <ul class="steps steps-vertical">
                    <li class="step step-primary">Step 1</li>
                    <li class="step step-primary">Step 2</li>
                    <li class="step">Step 3</li>
                    <li class="step">Step 4</li>
                </ul>
            </div>
        </div>

        <!-- alert -->
        <div class="flex flex-col gap-3">
            <div class="alert">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    class="h-6 w-6 flex-shrink-0 stroke-info"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                </svg>
                <span>12 unread messages. Tap to see.</span>
            </div>
            <div class="alert alert-info">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    class="h-6 w-6 flex-shrink-0 stroke-current"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                </svg>
                <span>New software update available.</span>
            </div>
            <div class="alert alert-success">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6 flex-shrink-0 stroke-current"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                </svg>
                <span>Your purchase has been confirmed!</span>
            </div>
            <div class="alert alert-warning">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6 flex-shrink-0 stroke-current"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                    />
                </svg>
                <span>Warning: Invalid email address!</span>
            </div>
            <div class="alert alert-error">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6 flex-shrink-0 stroke-current"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                </svg>
                <span>Error! Task failed successfully.</span>
            </div>
        </div>
        <!-- table-->
        <div
            class="w-full overflow-x-auto rounded-xl border border-base-content/10"
        >
            <table class="table w-full">
                <!-- head -->
                <thead>
                    <tr>
                        <th>
                            <label>
                                <input type="checkbox" class="checkbox" />
                            </label>
                        </th>
                        <th>Name</th>
                        <th>Job</th>
                        <th>Favorite Color</th>
                        <th />
                    </tr>
                </thead>
                <tbody>
                    <!-- row 1 -->
                    <tr>
                        <th>
                            <label>
                                <input type="checkbox" class="checkbox" />
                            </label>
                        </th>
                        <td>
                            <div class="flex items-center space-x-3">
                                <div class="avatar">
                                    <div class="mask mask-squircle h-12 w-12">
                                        <img
                                            src="https://placehold.co/48"
                                            alt="Avatar Tailwind CSS Component"
                                        />
                                    </div>
                                </div>
                                <div>
                                    <div class="font-bold">Hart Hagerty</div>
                                    <div class="text-sm opacity-50">
                                        United States
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            Zemlak, Daniel and Leannon
                            <br />
                            <span class="badge badge-ghost badge-sm"
                                >Desktop Support Technician</span
                            >
                        </td>
                        <td>Purple</td>
                        <th>
                            <button class="btn btn-ghost btn-xs">details</button
                            >
                        </th>
                    </tr>
                    <!-- row 2 -->
                    <tr>
                        <th>
                            <label>
                                <input type="checkbox" class="checkbox" />
                            </label>
                        </th>
                        <td>
                            <div class="flex items-center space-x-3">
                                <div class="avatar">
                                    <div class="mask mask-squircle h-12 w-12">
                                        <img
                                            src="https://placehold.co/48"
                                            alt="Avatar Tailwind CSS Component"
                                        />
                                    </div>
                                </div>
                                <div>
                                    <div class="font-bold">Brice Swyre</div>
                                    <div class="text-sm opacity-50">China</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            Carroll Group
                            <br />
                            <span class="badge badge-ghost badge-sm"
                                >Tax Accountant</span
                            >
                        </td>
                        <td>Red</td>
                        <th>
                            <button class="btn btn-ghost btn-xs">details</button
                            >
                        </th>
                    </tr>
                    <!-- row 3 -->
                    <tr>
                        <th>
                            <label>
                                <input type="checkbox" class="checkbox" />
                            </label>
                        </th>
                        <td>
                            <div class="flex items-center space-x-3">
                                <div class="avatar">
                                    <div class="mask mask-squircle h-12 w-12">
                                        <img
                                            src="https://placehold.co/48"
                                            alt="Avatar Tailwind CSS Component"
                                        />
                                    </div>
                                </div>
                                <div>
                                    <div class="font-bold">Marjy Ferencz</div>
                                    <div class="text-sm opacity-50">
                                        Ukraine
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            Rowe-Schoen
                            <br />
                            <span class="badge badge-ghost badge-sm"
                                >Office Assistant I</span
                            >
                        </td>
                        <td>Crimson</td>
                        <th>
                            <button class="btn btn-ghost btn-xs">details</button
                            >
                        </th>
                    </tr>
                    <!-- row 4 -->
                    <tr>
                        <th>
                            <label>
                                <input type="checkbox" class="checkbox" />
                            </label>
                        </th>
                        <td>
                            <div class="flex items-center space-x-3">
                                <div class="avatar">
                                    <div class="mask mask-squircle h-12 w-12">
                                        <img
                                            src="https://placehold.co/48"
                                            alt="Avatar Tailwind CSS Component"
                                        />
                                    </div>
                                </div>
                                <div>
                                    <div class="font-bold">Yancy Tear</div>
                                    <div class="text-sm opacity-50">Brazil</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            Wyman-Ledner
                            <br />
                            <span class="badge badge-ghost badge-sm"
                                >Community Outreach Specialist</span
                            >
                        </td>
                        <td>Indigo</td>
                        <th>
                            <button class="btn btn-ghost btn-xs">details</button
                            >
                        </th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <article class="container prose mx-auto mt-8 lg:prose-xl">
        <Lorem />
    </article>
</div>
