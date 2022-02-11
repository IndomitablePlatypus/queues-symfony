<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220211115322 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Cards';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
            CREATE TABLE cards (
                id uuid NOT NULL, 
                plan_id uuid NOT NULL,
                customer_id uuid NOT NULL,
                description text NOT NULL,
                issued_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
                achievements JSONB NOT NULL,
                requirements JSONB NOT NULL,
                satisfied_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
                completed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
                revoked_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
                blocked_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
                created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
                updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql('CREATE INDEX cards_plans_id_idx ON "cards" (plan_id)');
        $this->addSql('CREATE INDEX cards_users_id_idx ON "cards" (customer_id)');
        $this->addSql('CREATE INDEX cards_issued_at_idx ON "cards" (issued_at)');
        $this->addSql('CREATE INDEX cards_satisfied_at_idx ON "cards" (satisfied_at)');
        $this->addSql('CREATE INDEX cards_completed_at_idx ON "cards" (completed_at)');
        $this->addSql('CREATE INDEX cards_revoked_at_idx ON "cards" (revoked_at)');
        $this->addSql('CREATE INDEX cards_blocked_at_idx ON "cards" (blocked_at)');
        $this->addSql('COMMENT ON COLUMN "cards".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "cards".plan_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "cards".customer_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE "cards" ADD CONSTRAINT fk_cards_plans_plan_id FOREIGN KEY (plan_id) REFERENCES "plans" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE "cards"');
    }
}
