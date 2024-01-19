<?php
loadPartial('head');
loadPartial('navbar');
loadPartial('showcase');
loadPartial('top-banner');
?>

<section class="flex justify-center items-center mt-20">
    <div class="bg-white p-8 rounded-lg shadow-md w-full md:w-600 mx-6">
        <h2 class="text-4xl text-center font-bold mb-4">Edit Job Listing</h2>
        <form method="POST" action="/listings/<?= $listing->id; ?>">
            <input type="hidden" name="_method" value="PUT">
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-500">
                Job Info
            </h2>
            <?= loadPartial('errors', ['errors' => $errors ?? '']); ?>
            <div class="mb-4">
                <input type="text" value="<?= $listing->title ?? ''; ?>" name="title" placeholder="Job Title" class="w-full px-4 py-2 border rounded focus:outline-none" />
            </div>
            <div class="mb-4">
                <textarea name="description" placeholder="Job Description" class="w-full px-4 py-2 border rounded focus:outline-none"><?= $listing->description ?? ''; ?></textarea>
            </div>
            <div class="mb-4">
                <input type="text" value="<?= $listing->salary ?? ''; ?>" name="salary" placeholder="Annual Salary" class="w-full px-4 py-2 border rounded focus:outline-none" />
            </div>
            <div class="mb-4">
                <input type="text" value="<?= $listing->requirements ?? ''; ?>" name="requirements" placeholder="Requirements" class="w-full px-4 py-2 border rounded focus:outline-none" />
            </div>
            <div class="mb-4">
                <input type="text" value="<?= $listing->benefits ?? ''; ?>" name="benefits" placeholder="Benefits" class="w-full px-4 py-2 border rounded focus:outline-none" />
            </div>
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-500">
                Company Info & Location
            </h2>
            <div class="mb-4">
                <input type="text" value="<?= $listing->company ?? ''; ?>" name="company" placeholder="Company Name" class="w-full px-4 py-2 border rounded focus:outline-none" />
            </div>
            <div class="mb-4">
                <input type="text" value="<?= $listing->address ?? ''; ?>" name="address" placeholder="Address" class="w-full px-4 py-2 border rounded focus:outline-none" />
            </div>
            <div class="mb-4">
                <input type="text" value="<?= $listing->city ?? ''; ?>" name="city" placeholder="City" class="w-full px-4 py-2 border rounded focus:outline-none" />
            </div>
            <div class="mb-4">
                <input type="text" value="<?= $listing->state ?? ''; ?>" name="state" placeholder="State" class="w-full px-4 py-2 border rounded focus:outline-none" />
            </div>
            <div class="mb-4">
                <input type="text" value="<?= $listing->phone ?? ''; ?>" name="phone" placeholder="Phone" class="w-full px-4 py-2 border rounded focus:outline-none" />
            </div>
            <div class="mb-4">
                <input type="email" value="<?= $listing->email ?? ''; ?>" name="email" placeholder="Email Address For Applications" class="w-full px-4 py-2 border rounded focus:outline-none" />
            </div>
            <button class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 my-3 rounded focus:outline-none">
                Save
            </button>
            <a href="/listings/<?= $listing->id; ?>" class="block text-center w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded focus:outline-none">
                Cancel
            </a>
        </form>
    </div>
</section>

<?php
loadPartial('bottom-banner');
loadPartial('footer');
?>