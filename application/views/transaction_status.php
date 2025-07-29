<!doctype html>
<html lang="en" data-theme="light">

<head>
    <!-- Keep your existing head content -->
    <title>Transaction Status - STEPAKASH</title>
</head>

<body class="font-sans bg-gray-50 text-gray-800 dark:bg-gray-900 dark:text-gray-100 min-h-screen flex flex-col">
    <!-- Header (same as your home page) -->
    <header class="fixed top-0 left-0 right-0 h-16 bg-white dark:bg-dark shadow-sm z-50 border-b border-gray-100 dark:border-gray-800">
        <!-- Your existing header content -->
    </header>

    <!-- Main Content -->
    <main class="flex-1 pt-16 pb-20 px-4">
        <div class="container mx-auto max-w-3xl">
            <div class="mt-12"></div>

            <!-- Transaction Status Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold">Transaction Status</h2>
                        <span class="px-3 py-1 rounded-full text-sm font-medium 
                            <?php echo $transaction['status'] === 'completed' ? 'bg-green-100 text-green-800' : ($transaction['status'] === 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') ?>">
                            <?php echo ucfirst($transaction['status']) ?>
                        </span>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-300">Transaction ID:</span>
                            <span class="font-medium"><?php echo $transaction['token'] ?></span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-300">Amount:</span>
                            <span class="font-medium">KES <?php echo number_format($transaction['amount'], 2) ?></span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-300">CR Number:</span>
                            <span class="font-medium"><?php echo $transaction['cr_number'] ?></span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-300">Status:</span>
                            <span class="font-medium">
                                <?php if ($transaction['status'] === 'completed'): ?>
                                    Completed successfully
                                <?php elseif ($transaction['status'] === 'failed'): ?>
                                    Failed - <?php echo $transaction['error_message'] ?? 'Unknown error' ?>
                                <?php else: ?>
                                    Processing...
                                    <div class="inline-block ml-2 animate-spin rounded-full h-4 w-4 border-t-2 border-b-2 border-primary"></div>
                                <?php endif; ?>
                            </span>
                        </div>

                        <?php if ($transaction['status'] === 'pending'): ?>
                            <div class="pt-4">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    This page will automatically refresh to update the status.
                                    Please don't close this window.
                                </p>
                                <meta http-equiv="refresh" content="5">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700 p-4 flex justify-between items-center">
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        Last updated: <?php echo date('H:i:s') ?>
                    </span>

                    <?php if ($transaction['status'] !== 'pending'): ?>
                        <a href="<?php echo base_url() ?>home" class="text-sm text-primary dark:text-primary-dark hover:underline">
                            Return to Home
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Bottom Navigation (same as your home page) -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 shadow-lg border-t border-gray-100 dark:border-gray-700 z-40">
        <!-- Your existing bottom navigation -->
    </nav>

    <!-- Your existing scripts -->
</body>

</html>