<?php

/**
 * Feature : Setup
 * As an anonymous user, I need to be able to see the setup page with an invitation to install the plugin.
 *
 * @TODO : Test a scenario where the key is not compatible with GPG on server side.
 * @TODO : Test scenario with a key that has matching information (same name and email).
 * @TODO : Test a scenario where the name of the user has to be altered.
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class SetupTest extends PassboltSetupTestCase {

	/**
	 * Scenario:  I can see the setup page with instructions to install the plugin
	 * Given      I am an anonymous user with no plugin on the registration page
	 * And        I follow the registration process and click on submit
	 * And        I click on the link get started in the email I received
	 * Then       I should reach the setup page
	 * And        the url should look like resource://passbolt-at-passbolt-dot-com/passbolt-firefox-addon/data/setup.html
	 * And        I should see the text "Nice one! Firefox plugin is installed and up to date. You are good to go!"
	 * And        I should see that the domain in the url check textbox is the same as the one configured.
	 */
	public function testCanSeeSetupPageWithFirstPluginSection() {
		// Register John Doe as a user.
		$this->registerUser('John', 'Doe', 'johndoe@passbolt.com');

		// We check below that we can read the invitation email and click on the link get started.
		// Get last email.
		$this->getUrl('seleniumTests/showLastEmail/' . urlencode('johndoe@passbolt.com'));
		// Follow the link in the email.
		$this->followLink("get started");
		// Test that the url is the plugin one.
		$this->assertUrlMatch('/resource:\/\/passbolt-at-passbolt-dot-com\/data\/setup.html/');

		// Test that the plugin confirmation message is displayed.
		$this->waitUntilISee('.plugin-check.success', '/Firefox plugin is installed and up to date/i');

		// Test that the domain in the url check textbox is the same as the one configured.
		$domain = $this->findById("js_setup_domain")->getAttribute('value');
		$this->assertEquals(Config::read('passbolt.url'), $domain);

		// Since content was edited, we reset the database
		$this->resetDatabase();
	}

	/**
	 * Scenario :   I go through the setup and I make sure the navigation buttons and menu items are working properly.
	 * Given        I am an anonymous user with the plugin on the first page of the setup
	 * Then         the menu "1. get the plugin" should be selected
	 * When         I check the domain validation checkbox
	 * And          I click on the link "Next"
	 * Then         I should see a page with a title "Create a new key"
	 * And          the menu "2. Define your keys" should be selected
	 * When         I click on the link "Cancel"
	 * Then         I should be back on the 1st step.
	 * When         I check the domain validation checkbox.
	 * And          I click "Next"
	 * When         I click "Import"
	 * Then         I should see a page where I can import my keys
	 * When         I click "Create"
	 * Then         I should be back on the page to generate a key
	 * When         I click "Next" again
	 * Then         I should be at the step 3
	 * And          I should see a page with title "Now let's setup your master password"
	 * And          The menu "3. Set a master password" should be selected
	 * When         I click "Cancel"
	 * Then         I should be back at step 2
	 * And          the menu "2. Define your keys should be selected"
	 * When         I click "Next"
	 * Then         I should be back at step 3
	 * When         I fill up a master password in the password field
	 * And          I click "Next"
	 * Then         I should reach a page saying that the secret and public key is generating
	 * And          I should wait until the key is generated
	 * And          I should reach the next step saying that the secret key is ready.
	 * And          I should see that the menu "3. Set a master password" is selected
	 * When         I click "Cancel"
	 * Then         I should be back at the step "enter master password"
	 * When         I enter the master password and click Next
	 * Then         I should see that the key generates again
	 * When         The key is generated and I reach the next step "Success! Your secret key is ready"
	 * And          I click "Next"
	 * Then         I should reach the next step
	 * And          I should see "Set a security token" as the title
	 * When         I click "Next"
	 * Then         I should reach the final step where I am being redirected
	 * And          The "Login !" menu should be selected
	 *
	 * @throws Exception
	 */
	public function testNavigation() {
		// Register John Doe as a user.
		$this->registerUser('John', 'Doe', 'johndoe@passbolt.com');

		// Go to Setup page.
		$this->goToSetup('johndoe@passbolt.com');
        // Wait until I see the first page of setup.
		$this->waitForSection('domain_check');
		// Assert menu is selected.
		$this->assertMenuIsSelected($this->getSectionInfo('domain_check', 'menu_item'));
		sleep(2);
		// Check box domain check.
		$this->checkCheckbox('js_setup_domain_check');
		// Click Next.
		$this->clickLink("Next");
		// Wait
		$this->waitForSection('generate_key_form');
		// Assert menu is selected.
		$this->assertMenuIsSelected($this->getSectionInfo('generate_key_form', 'menu_item'));
		// Test that Cancel button is working.
		$this->clickLink('Cancel');
		// Test that we are back at step 1.
		$this->waitForSection('domain_check');
		// Assert menu is selected.
		$this->assertMenuIsSelected($this->getSectionInfo('domain_check', 'menu_item'));
		sleep(2);
		// Check box domain check.
		$this->checkCheckbox('js_setup_domain_check');
		// Click Next.
		$this->clickLink("Next");
		// Wait
		$this->waitForSection('generate_key_form');
		// Click on import.
		$this->clickLink('import');
		// Wait
		$this->waitForSection('import_key_form');
		// Click on create.
		$this->clickLink('create');
		// Wait
		$this->waitForSection('generate_key_form');
		// Click Next.
		$this->clickLink("Next");
		// Wait until we see the title Master password.
		$this->waitForSection('generate_key_master_password');
		// Assert menu is selected.
		$this->assertMenuIsSelected($this->getSectionInfo('generate_key_master_password', 'menu_item'));
		// Test that Cancel button is working.
		$this->clickLink('Cancel');
		// Wait
		$this->waitUntilISee('#js_step_title', '/Create a new key/i');
		// Assert menu is selected.
		$this->assertMenuIsSelected($this->getSectionInfo('generate_key_form', 'menu_item'));
		// Click Next.
		$this->clickLink("Next");
		// Wait until we see the title Master password.
		$this->waitForSection('generate_key_master_password');
		// Fill master key.
		$this->inputText('js_field_password', 'johndoemasterpassword');
		// Press Next.
		$this->clickLink("Next");
		// Wait to reach the page.
		$this->waitForSection('generate_key_progress');
		// Wait until the key is generated.
		$this->waitForSection('generate_key_done');
		// Assert menu is selected.
		$this->assertMenuIsSelected($this->getSectionInfo('generate_key_done', 'menu_item'));
		// The key is generated, we can click Next.
		$this->clickLink("Cancel");
		// Wait until we see the title Master password.
		$this->waitForSection('generate_key_master_password');
		// Assert menu is selected.
		$this->assertMenuIsSelected($this->getSectionInfo('generate_key_master_password', 'menu_item'));
		// Fill master key.
		$this->inputText('js_field_password', 'johndoemasterpassword');
		// Press Next.
		$this->clickLink("Next");
		// Wait to reach the page.
		$this->waitForSection('generate_key_progress');
		// Wait until we see the title Master password.
		$this->waitForSection('generate_key_done');
		// Assert menu is selected.
		$this->assertMenuIsSelected($this->getSectionInfo('generate_key_done', 'menu_item'));
		// Press Next.
		$this->clickLink("Next");
		// Wait.
		$this->waitForSection('security_token');
		// Assert menu is selected.
		$this->assertMenuIsSelected($this->getSectionInfo('security_token', 'menu_item'));
		// Test that Cancel button is working.
		$this->clickLink('Cancel');
		// Wait until we see the title Your secret key is ready.
		$this->waitForSection('generate_key_done');
		// Press Next.
		$this->clickLink("Next");
		// Wait.
		$this->waitForSection('security_token');
		// Press Next.
		$this->clickLink("Next");
		// Test that we are at the final step.
		$this->waitForSection('login_redirect');
		// Assert menu is selected.
		$this->assertMenuIsSelected($this->getSectionInfo('login_redirect', 'menu_item'));
		// Since content was edited, we reset the database
		$this->resetDatabase();
	}


	/**
	 * Scenario     As an AP using the setup, I should be able to go through all the steps of the setup
	 * Given        I am registered and on the first page of the setup
	 * Then         I should be able to verify the domain
	 * When         I click "Next"
	 * Then         I should be able to prepare the generation of my key
	 * When         I click "Next"
	 * Then         I should be able to enter a master password
	 * When         I click "Next"
	 * Then         The key should be generated and I should be able to download it
	 * When         I click "Next"
	 * Then         I should be able to choose a security token
	 * When         I click "Next"
	 * Then         I should be able to enter a password for my account
	 * When         I click "Next"
	 * Then         I should observe that I am logged in inside the app
	 * And          I should see my name and email in the account section
	 * @throws Exception
	 */
	public function testCanFollowSetupWithDefaultSteps() {
		$john = User::get('john');
		// Register John Doe as a user.
		$this->registerUser($john['FirstName'], $john['LastName'], $john['Username']);

		// Go to setup page and register
		$this->goToSetup($john['Username']);
		$this->completeRegistration();

		$this->loginAs([
			'Username' => $john['Username'],
			'MasterPassword' => $john['MasterPassword']
		]);
		// Check we are logged in.
		$this->waitCompletion();
		$this->waitUntilISee('#js_app_controller.ready');
		// Check that the name is ok.
		$this->assertElementContainsText(
			$this->findByCss('.header .user.profile .details .name'),
			$john['FirstName'] . ' ' . $john['LastName']
		);
		// Check that the email is ok.
		$this->assertElementContainsText(
			$this->findByCss('.header .user.profile .details .email'),
			$john['Username']
		);

		// Since content was edited, we reset the database
		$this->resetDatabase();
	}

	/**
	 * Scenario :  As an AP I should be able to import my own key during the setup
	 * Given       I am registered as John Doe, and I go to the setup
	 * When        I go through the setup until the import key step
	 * And         I test that I can import my key
	 * Then        I should see that the setup behaves as it should (defined in function testStepImportKey)
	 * When        I complete the setup
	 * Then        I should be logged in inside the app
	 * And         I should be able to visually confirm my account information
	 * @throws Exception
	 */
	public function testFollowSetupWithImportKey() {
		$key = Gpgkey::get(['name' => 'johndoe']);

		$john = User::get('john');
		// Register John Doe as a user.
		$this->registerUser($john['FirstName'], $john['LastName'], $john['Username']);

		// Go to setup page and register
		$this->goToSetup($john['Username']);
		// Wait
		$this->waitForSection('domain_check');
		// Wait for the server key to be retrieved.
		sleep(2);
		// Check box domain check.
		$this->checkCheckbox('js_setup_domain_check');
		// Click Next.
		$this->clickLink("Next");
		// Wait
		$this->waitForSection('generate_key_form');
		// Click on import.
		$this->clickLink('import');
		// Wait
		$this->waitForSection('import_key_form');
		// Test step import key.
		$this->completeStepImportKey($key);
		// Click Next
		$this->clickLink('Next');
		// Wait until next step.
		$this->waitForSection('security_token');
		// Click Next.
		$this->clickLink("Next");
		// Wait until sees next step.
		$this->waitForSection('login_redirect');
		// Wait until I reach the login page
		$this->waitUntilISee('.information h2', '/Welcome back!/');

		// Login as john doe
		$this->loginAs([
			'Username' => $key['owner_email'],
			'MasterPassword' => $key['masterpassword']
		]);

		$this->waitCompletion();
		// Check we are logged in.
		$this->waitUntilISee('.page.password', null, 20);
		// Check that the name is ok.
		$this->assertElementContainsText(
			$this->findByCss('.header .user.profile .details .name'),
			$key['owner_name']
		);
		// Check that the email is ok.
		$this->assertElementContainsText(
			$this->findByCss('.header .user.profile .details .email'),
			$key['owner_email']
		);

		// Since content was edited, we reset the database
		$this->resetDatabase();
	}

	/**
	 * Scenario :   As an AP, I should not be able to do the setup after my account has been activated
	 * Given I click again on the link in the invitation email
	 * Then  I should not see the setup again
	 * And   I should see a page with a "Token not found" error
	 * @throws Exception
	 */
	public function testSetupNotAccessibleAfterAccountValidation() {
		// Register John Doe as a user.
		$this->registerUser('John', 'Doe', 'johndoe@passbolt.com');

		// Get last email.
		$this->getUrl('seleniumTests/showLastEmail/' . urlencode('johndoe@passbolt.com'));
		// Remember setup url. (We will use it later).
		$linkElement = $this->findLinkByText('get started');
		$setupUrl = $linkElement->getAttribute('href');

		// Go to setup page.
		$this->goToSetup('johndoe@passbolt.com');
		$this->completeRegistration();

		// Go to url remembered above.
		$this->driver->get($setupUrl);
		$this->waitUntilISee('h2', '/Token not found/');

		// Since content was edited, we reset the database
		$this->resetDatabase();
	}

	/**
	 * Scenario :   As an AP, I should be able to complete 2 setup consecutively.
	 * Given I have completed already one registration + setup successfully.
	 * When  I register again with a different username
	 * Then  I should be able to complete the setup another time without error.
	 * @throws Exception
	 */
	public function testSetupMultipleTimes() {
		// Register John Doe as a user.
		$john = User::get('john');
		$this->registerUser($john['FirstName'], $john['LastName'], $john['Username']);

		// Go to setup page.
		$this->goToSetup($john['Username']);
		$this->completeRegistration($john);

		// Register Curtis Mayfield as a user.
		$curtis = User::get('curtis');
		$this->registerUser($curtis['FirstName'], $curtis['LastName'], $curtis['Username']);

		// Go to setup page.
		$this->goToSetup($curtis['Username']);

		// Wait until I see the setup section domain check.
		$this->waitForSection('domain_check');

		// Complete registration.
		$this->completeRegistration($curtis);

		// Database has changed, reset it.
		$this->resetDatabase();
	}


	/**
	 * Scenario :   As an AP, I should be able to restart the setup where I left it.
	 * Given I have completed already one registration and setup, but left the setup in the middle.
	 * When  I click again on the setup link in the email I received
	 * Then  I should see that the setup is restarting at the same screen where I was last time.
	 * When  I press Cancel
	 * Then  I should see that the setup is at the step before.
	 * @throws Exception
	 */
	public function testSetupRestartWhereItWasLeft() {
		// Register John Doe as a user.
		$john = User::get('john');
		$this->registerUser($john['FirstName'], $john['LastName'], $john['Username']);

		// Go to setup page.
		$this->goToSetup($john['Username']);

		// Test step domain verification.
		$this->completeStepDomainVerification();

		// Click Next.
		$this->clickLink("Next");

		// test step that prepares key creation.
		$this->completeStepPrepareCreateKey($john);

		// Fill comment.
		$this->clickLink("Next");

		// Go to setup page.
		// Get last email.
		$this->getUrl('seleniumTests/showLastEmail/' . urlencode($john['Username']));

		// Remember setup url. (We will use it later).
		$linkElement = $this->findLinkByText('get started');
		$setupUrl = $linkElement->getAttribute('href');

		// Go to url remembered above.
		$this->driver->get($setupUrl);

		// Wait until master password section appears.
		$this->waitForSection('generate_key_master_password');

		// Test that Cancel button is working.
		$this->clickLink('Cancel');

		// I should see the previous section generate_key_form.
		$this->waitForSection('generate_key_form');

		// Database has changed, reset it.
		$this->resetDatabase();
	}
}